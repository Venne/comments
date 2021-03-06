<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Venne\Comments\Components;

use Venne\Forms\IFormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CommentFormFactory implements \Venne\Forms\IFormFactory
{

	/** @var \Venne\Forms\IFormFactory */
	private $formFactory;

	/**
	 * @param \Venne\Forms\IFormFactory $formFactory
	 */
	public function __construct(IFormFactory $formFactory)
	{
		$this->formFactory = $formFactory;
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function create()
	{
		$form = $this->formFactory->create();

		$form->addGroup();
		$form->addTextArea('text', 'Text');

		$form->setCurrentGroup();
		$form->addSubmit('_submit', 'Save');

		return $form;
	}

}
