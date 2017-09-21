<?php

namespace AcMarche\LunchBundle\DataFixtures\ORM;

Class FixtureConstant
{
    const CATEGORIES = [
        'Jeux',
        'Outils bricolages',
        'Papeterie',
    ];

    const COMMERCES = ["lobet", "malice","memo"];

    const PRODUIS_JEUX = [
        ["malice" => 'Cheval bascule'],
        ["malice" => 'Voiture batman'],
        ["malice" => 'Ballon extérieur'],
        ["malice" => 'Poupée barbie'],
    ];

    const PRODUIS_OUTILS = [
        ["lobet" => 'Marteau'],
        ["lobet" => 'Scie'],
        ["lobet" => 'Tourne vis'],
    ];

    const PRODUIS_PAPETERIE = [
        ["memo" => 'Gomme'],
        ["memo" => 'Crayon'],
        ["memo" => 'Marqueur'],
    ];

}