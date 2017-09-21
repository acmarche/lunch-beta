<?php

namespace AcMarche\LunchBundle\Security;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Entity\Supplement;
use AcMarche\SecurityBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * It grants or denies permissions for actions related to blog posts (such as
 * showing, editing and deleting posts).
 *
 * See http://symfony.com/doc/current/security/voters.html
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class PanierVoter extends Voter
{
    // Defining these constants is overkill for this simple application, but for real
    // applications, it's a recommended practice to avoid relying on "magic strings"

    const ADD = 'new';
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        // this voter is only executed for three specific permissions on Post objects
        return $subject instanceof CommandeInterface && in_array($attribute, [self::ADD, self::SHOW, self::EDIT, self::DELETE]);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $commande, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if (!$user->hasRole('ROLE_LUNCH_CLIENT'))
            return false;

        if ($user->getUsername() == $commande->getUser())
            return true;

        return false;
    }
}
