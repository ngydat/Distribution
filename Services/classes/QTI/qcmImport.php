<?php

/**
 * To import a QCM question in QTI
 *
 */

namespace UJM\ExoBundle\Services\classes\QTI;

use UJM\ExoBundle\Entity\Choice;
use UJM\ExoBundle\Entity\InteractionQCM;

class qcmImport extends qtiImport
{
    protected $interactionQCM;

    /**
     * Implements the abstract method
     *
     * @access public
     * @param qtiRepository $qtiRepos
     *
     */
    public function import(qtiRepository $qtiRepos, \DOMDocument $document)
    {
        $this->qtiRepos = $qtiRepos;
        $this->document = $document;
        $this->getQTICategory();
        $this->initAssessmentItem();

        $this->createQuestion();

        $this->createInteraction();
        $this->interaction->setType('InteractionQCM');
        $this->doctrine->getManager()->persist($this->interaction);
        $this->doctrine->getManager()->flush();

        $this->createInteractionQCM();

        //TODO resources liées
    }

    /**
     * Implements the abstract method
     *
     * @access protected
     *
     */
    protected function getPrompt()
    {
        $ib = $this->assessmentItem->getElementsByTagName("itemBody")->item(0);
        $ci = $ib->getElementsByTagName("choiceInteraction")->item(0);
        if ($ci->getElementsByTagName("prompt")->item(0)) {
            $prompt = $ci->getElementsByTagName("prompt")->item(0)->nodeValue;
        } else {
            $prompt = $this->container->get('translator')->trans('qti_import_qcm_prompt');
        }

        return $prompt;
    }

    /**
     * Create the InteractionQCM object
     *
     * @access protected
     *
     */
    protected function createInteractionQCM()
    {
        $rp = $this->assessmentItem->getElementsByTagName("responseProcessing");
        $this->interactionQCM = new InteractionQCM();
        $this->interactionQCM->setInteraction($this->interaction);
        $this->getShuffle();
        $this->getQCMType();
        if ($rp->item(0) && $rp->item(0)->getElementsByTagName("responseCondition")->item(0)) {
            $this->interactionQCM->setWeightResponse(false);
            $this->getGlobalScore();
        } else {
            $this->interactionQCM->setWeightResponse(true);
        }
        $this->doctrine->getManager()->persist($this->interactionQCM);
        $this->doctrine->getManager()->flush();
        $this->createChoices();
    }

    /**
     * Get shuffle
     *
     * @access protected
     *
     */
    protected function getShuffle()
    {
        $ib = $this->assessmentItem->getElementsByTagName("itemBody")->item(0);
        $ci = $ib->getElementsByTagName("choiceInteraction")->item(0);
        if ($ci->hasAttribute("shuffle") && $ci->getAttribute("shuffle") == 'true') {
            $this->interactionQCM->setShuffle(TRUE);
        } else {
            $this->interactionQCM->setShuffle(FALSE);
        }
    }

    /**
     * Get Type QCM
     *
     * @access protected
     *
     */
    protected function getQCMType()
    {
        $ri = $this->assessmentItem->getElementsByTagName("responseDeclaration")->item(0);
        if ($ri->hasAttribute("cardinality") && $ri->getAttribute("cardinality") == 'multiple') {
             $type = $this->doctrine
                          ->getManager()
                          ->getRepository('UJMExoBundle:TypeQCM')
                          ->findOneBy(array('code' => 1));

            $this->interactionQCM->setTypeQCM($type);
        } else {
            $type = $this->doctrine
                          ->getManager()
                          ->getRepository('UJMExoBundle:TypeQCM')
                          ->findOneBy(array('code' => 2));

            $this->interactionQCM->setTypeQCM($type);
        }
    }

    /**
     * Create choices
     *
     * @access protected
     *
     */
    protected function createChoices()
    {
        $order = 1;
        $ib = $this->assessmentItem->getElementsByTagName("itemBody")->item(0);
        $ci = $ib->getElementsByTagName("choiceInteraction")->item(0);

        foreach($ci->getElementsByTagName("simpleChoice") as $simpleChoice) {
            $choice = new Choice();
            if ($simpleChoice->hasAttribute("fixed")
                    && $simpleChoice->getAttribute("fixed") == 'true') {
                $choice->setPositionForce(true);
            }
            $feedback = $simpleChoice->getElementsByTagName("feedbackInline");
            if ($feedback->item(0)) {
                $choice->setFeedback($feedback->item(0)->nodeValue);
                $simpleChoice->removeChild($feedback->item(0));
            }
            $choice->setLabel($simpleChoice->nodeValue);
            $choice->setOrdre($order);
            $choice->setWeight($this->getWeightChoice($simpleChoice->getAttribute("identifier")));
            $choice->setRightResponse($this->getRightResponse($simpleChoice->getAttribute("identifier")));
            $choice->setInteractionQCM($this->interactionQCM);
            $this->doctrine->getManager()->persist($choice);
            $this->doctrine->getManager()->flush();
            $order ++;
        }
    }

    /**
     * Get weightChoice
     *
     * @access protected
     *
     * @param String $identifier identifier of choice in the qti file
     *
     * return float
     */
    protected function getWeightChoice($identifier)
    {
        $weight = 0;
        $ri = $this->assessmentItem->getElementsByTagName("responseDeclaration")->item(0);
        $mapping = $ri->getElementsByTagName("mapping");
        if ($mapping->item(0)) {
            $mps = $mapping->item(0)->getElementsByTagName("mapEntry");
            foreach ($mps as $mp) {
                if ($mp->hasAttribute("mappedValue")
                        && $mp->hasAttribute("mapKey")
                        && $mp->getAttribute("mapKey") == $identifier) {
                    $weight = $mp->getAttribute("mappedValue");
                    break;
                }
            }
        }

        return $weight;
    }

    /**
     * Get rightResponse
     *
     * @access protected
     *
     * @param String $identifier identifier of choice in the qti file
     *
     * return boolean
     */
    protected function getRightResponse($identifier)
    {
        $rightResponse = false;
        $ri = $this->assessmentItem->getElementsByTagName("responseDeclaration")->item(0);
        $cr = $ri->getElementsByTagName("correctResponse")->item(0);
        $values = $cr->getElementsByTagName("value");
        foreach ($values as $value) {
            if ($identifier == $value->nodeValue) {
                $rightResponse = true;
                break;
            }
        }

        return $rightResponse;
    }

    /**
     * Get score for the right response and the false response
     *
     * @access protected
     *
     */
    protected function getGlobalScore()
    {

    }

}
