<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sonata\PageBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * PageExtension
 *
 *
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ActionBlockService extends BaseBlockService
{
    private $kernel;

    public function __construct($name, EngineInterface $templating, HttpKernelInterface $kernel)
    {
        parent::__construct($name, $templating);
        
        $this->kernel = $kernel;
    }

    public function execute(BlockInterface $block, $page, Response $response = null)
    {

        $params = array_merge($block->getSetting('parameters', array()), array('_block' => $block, '_page' => $page));
        
        return $this->render('SonataPageBundle:Block:block_core_action.html.twig', array(
            'content'   => $this->kernel->render($block->getSetting('action'), $params),
            'block'     => $block,
            'page'      => $page,
        ), $response);
    }

    public function validateBlock(BlockInterface $block)
    {
        // TODO: Implement validateBlock() method.
    }

    public function defineBlockForm(Form $form, BlockInterface $block)
    {
        $form->add(new \Symfony\Component\Form\TextField('action'));

        $parameters = new \Symfony\Component\Form\Form('parameters');
        
        foreach($block->getSetting('parameters', array()) as $name => $value) {
            $parameters->add(new \Symfony\Component\Form\TextField($name));
        }

        $form->add($parameters);
    }
}