<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use App\Service\ArticleService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArticleVoter extends Voter
{
    public const DELETE = 'delete';
    public const EDIT = 'edit';
    public const SHOW = 'show';
    public const COMMENT = 'comment';
    public const REGARD = 'regard';

    private const ACTIONS = [
        self::DELETE,
        self::EDIT,
        self::SHOW,
        self::COMMENT,
        self::REGARD,
    ];

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    protected function supports($attributes, $subject): bool
    {
        return $subject instanceof Article && \in_array($attributes, self::ACTIONS, true);
    }

    protected function voteOnAttribute($attribute, $article, TokenInterface $token): bool
    {
        $user = $token->getUser();

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($article, $user);
                break;
            case self::SHOW:
                return $this->canView($article);
                break;
            case self::DELETE:
                return $this->canDelete($article, $user);
                break;
            case self::COMMENT:
                return $this->canComment($user);
                break;
            case self::REGARD:
                return $this->canRegard($user);
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canEdit(Article $article, ?User $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        return $this->articleService->isEditableArticle($article) && $this->articleService->isAuthorArticle($user, $article);
    }

    public function canView(Article $article): bool
    {
        return $this->articleService->isViewableArticle($article);
    }

    public function canDelete(Article $article, ?User $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        return $this->articleService->isEditableArticle($article) && $this->articleService->isAuthorArticle($user, $article);
    }

    public function canComment(?User $user)
    {
        if (!$user instanceof User) {
            return false;
        }
    }

    public function canRegard(?User $user)
    {
        if (!$user instanceof User) {
            return false;
        }
    }
}