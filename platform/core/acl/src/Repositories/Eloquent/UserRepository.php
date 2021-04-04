<?php

namespace Platform\ACL\Repositories\Eloquent;

use Platform\ACL\Repositories\Interfaces\UserInterface;
use Platform\Support\Repositories\Eloquent\RepositoriesAbstract;

class UserRepository extends RepositoriesAbstract implements UserInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUniqueUsernameFromEmail($email)
    {
        $emailPrefix = substr($email, 0, strpos($email, '@'));
        $username = $emailPrefix;
        $offset = 1;
        while ($this->getFirstBy(['username' => $username])) {
            $username = $emailPrefix . $offset;
            $offset++;
        }

        $this->resetModel();

        return $username;
    }
}
