<?php

declare(strict_types=1);

namespace App\Gateway;

use App\ItOps\Breadcrumbs\BreadcrumbsBuilder;
use App\ItOps\Pagination\Paginator;
use App\ItOps\Symfony\Form\FormNormalizer\FormNormalizer;
use App\ItOps\Symfony\HttpFoundation\FlashBag\FlashBag;
use App\ItOps\Symfony\HttpFoundation\FlashBag\Type as FlashBagType;
use App\ItOps\Uuid\UuidGenerator;
use App\User\Credentials\User;
use Kenny1911\SymfonyAttributeForm\AttributeFormFactory;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @psalm-internal App\Gateway
 */
final readonly class ControllerHelper
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private RequestStack $requestStack,
        private UrlGeneratorInterface $urlGenerator,
        private AuthorizationCheckerInterface $authorizationChecker,
        private FormFactoryInterface $formFactory,
        private AttributeFormFactory $attributeFormFactory,
        private FormNormalizer $formNormalizer,
        private UuidGenerator $uuidGenerator,
        private Paginator $paginator,
        public BreadcrumbsBuilder $breadcrumbs,
        private FlashBag $flashBag,
        private ClockInterface $clock,
    ) {}

    public function currentUser(): ?User
    {
        $user = $this->tokenStorage->getToken()?->getUser();

        return ($user instanceof User) ? $user : null;
    }

    public function currentUserOrAccessDenied(): User
    {
        return $this->currentUser() ?? throw $this->createAccessDeniedException();
    }

    public function redirect(string $url, int $status = Response::HTTP_FOUND): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    public function redirectToRoute(string $name, array $parameters = [], int $status = Response::HTTP_FOUND): RedirectResponse
    {
        return $this->redirect($this->generateUrl($name, $parameters), $status);
    }

    /**
     * @param object $form Supports classes, marked by {@see Form} attribute and regular {@see FormInterface}
     */
    public function redirectToRouteWithFormQueryString(
        string $name,
        object $form,
        array $parameters = [],
        int $status = Response::HTTP_FOUND,
    ): RedirectResponse {
        if (false === $form instanceof FormInterface) {
            $form = $this->createAttributeFormBuilder($form::class, $form)->getForm();
        }

        return $this->redirectToRoute(
            $name,
            array_merge((array) $this->formNormalizer->normalize($form), $parameters),
            $status,
        );
    }

    /**
     * @param object $form Supports classes, marked by {@see Form} attribute and regular {@see FormInterface}
     */
    public function urlToRouteWithFormQueryString(string $name, object $form, array $parameters = []): string
    {
        return $this->redirectToRouteWithFormQueryString(name: $name, form: $form, parameters: $parameters)->getTargetUrl();
    }

    public function createNotFoundException(string $message = 'Not found'): NotFoundHttpException
    {
        return new NotFoundHttpException($message);
    }

    public function createAccessDeniedException(string $message = 'Access denied'): AccessDeniedHttpException
    {
        return new AccessDeniedHttpException($message);
    }

    public function createBadRequestException(string $message = 'Bad request'): BadRequestHttpException
    {
        return new BadRequestHttpException($message);
    }

    public function currentRequest(): Request
    {
        return $this->requestStack->getCurrentRequest() ?? throw new \LogicException('No request');
    }

    public function currentRequestRoute(): string
    {
        return $this->currentRequest()->attributes->getString('_route');
    }

    public function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    /**
     * @param non-empty-string $message
     */
    public function addFlash(FlashBagType $type, string $message): void
    {
        $this->flashBag->add($type, $message);
    }

    /**
     * @psalm-param UrlGeneratorInterface::ABSOLUTE_URL|UrlGeneratorInterface::ABSOLUTE_PATH|UrlGeneratorInterface::RELATIVE_PATH|UrlGeneratorInterface::NETWORK_PATH $referenceType
     */
    public function generateUrl(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        return $this->urlGenerator->generate($name, $parameters, $referenceType);
    }

    public function isGranted(mixed $attribute, mixed $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($attribute, $subject);
    }

    /**
     * @throws AccessDeniedHttpException
     */
    public function denyAccessUnlessGranted(mixed $attribute, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($attribute, $subject)) {
            throw $this->createAccessDeniedException($message);
        }
    }

    public function createFormBuilder(string $type = FormType::class, mixed $data = null, array $options = [], ?string $name = null): FormBuilderInterface
    {
        if (null === $name) {
            return $this->formFactory->createBuilder($type, $data, $options);
        }

        return $this->formFactory->createNamedBuilder($name, $type, $data, $options);
    }

    public function createForm(string $type = FormType::class, mixed $data = null, array $options = [], ?string $name = null): FormInterface
    {
        if (null === $name) {
            return $this->formFactory->create($type, $data, $options);
        }

        return $this->formFactory->createNamed($name, $type, $data, $options);
    }

    /**
     * @param class-string $dataClass
     * @param non-empty-string|null $name
     * @param array<string, mixed> $options
     * @param array<non-empty-string, array<string, mixed>> $fieldsOptions
     */
    public function createAttributeFormBuilder(
        string $dataClass,
        mixed $data = null,
        ?string $name = null,
        array $options = [],
        array $fieldsOptions = [],
    ): FormBuilderInterface {
        return $this->attributeFormFactory->createFormBuilder(
            dataClass: $dataClass,
            data: $data,
            name: $name,
            options: $options,
            fieldsOptions: $fieldsOptions,
        );
    }

    /**
     * @param class-string $dataClass
     * @param non-empty-string $submitLabel
     * @param non-empty-string|null $name
     * @param array<string, mixed> $options
     * @param array<non-empty-string, array<string, mixed>> $fieldsOptions
     */
    public function createAttributeForm(
        string $dataClass,
        string $submitLabel = 'Submit',
        mixed $data = null,
        ?string $name = null,
        array $options = [],
        array $fieldsOptions = [],
    ): FormInterface {
        return $this->createAttributeFormBuilder(
            dataClass: $dataClass,
            data: $data,
            name: $name,
            options: $options,
            fieldsOptions: $fieldsOptions,
        )
            ->add('submit', SubmitType::class, ['label' => $submitLabel])
            ->getForm();
    }

    public function isSubmitClicked(FormInterface $form, string $submitName): bool
    {
        if (false === $form->has($submitName)) {
            return false;
        }

        $submit = $form->get($submitName);

        if (false === $submit instanceof SubmitButton) {
            return false;
        }

        return $submit->isClicked();
    }

    public function generateUuid(): Uuid
    {
        return $this->uuidGenerator->generate();
    }

    /**
     * @template T
     *
     * @param callable(): non-negative-int $count
     * @param callable(non-negative-int, non-negative-int): iterable<T> $items Callback, that takes 2 arguments: offset and limit items on page
     * @param non-empty-string $pageName
     * @param non-empty-string $limitName
     * @param positive-int $limit
     *
     * @return PaginationInterface<array-key, T>
     */
    public function paginate(
        callable $count,
        callable $items,
        string $pageName = 'page',
        string $limitName = 'limit',
        int $limit = Paginator::DEFAULT_LIMIT,
    ): PaginationInterface {
        return $this->paginator->paginate($count, $items, $pageName, $limitName, $limit);
    }

    public function now(): \DateTimeImmutable
    {
        return $this->clock->now();
    }
}
