<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/09/17
 * Time: 16:27
 */

namespace AcMarche\LunchBundle;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandeParamConverter implements ParamConverterInterface
{

    /**
     * Stores the object in the request.
     *
     * @param Request $request The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
            $json = $request->attributes->get('commande');
var_dump($json);
exit();
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        var_dump($configuration);exit();
        // Si le nom de l'argument du contrôleur n'est pas "json", on n'applique pas le convertisseur
        if ('commande' !== $configuration->getName()) {
            return false;
        }

        return true;
    }
}