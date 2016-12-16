<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Models;

/**
 * Description of Prefere
 *
 * @author admin
 */
class Prefere {

    private $commentaire;


    /**
     * Get the value of Description of Prefere
     *
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set the value of Description of Prefere
     *
     * @param mixed commentaire
     *
     * @return self
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

}
