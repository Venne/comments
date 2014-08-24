<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Venne\Comments\DI;

use Nette\DI\Statement;
use Venne\System\DI\SystemExtension;
use Venne\Widgets\DI\WidgetsExtension;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CommentsExtension extends \Nette\DI\CompilerExtension
	implements
	\Kdyby\Doctrine\DI\IEntityProvider,
	\Venne\System\DI\IPresenterProvider
{

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->prefix('commentControlFactory'))
			->setImplement('Venne\Comments\Components\ICommentControlFactory')
			->setArguments(array(new Statement('@doctrine.dao', array('Venne\Comments\CommentEntity'))))
			->setInject(true);

		$container->addDefinition($this->prefix('commentsControlFactory'))
			->setImplement('Venne\Comments\Components\ICommentsControlFactory')
			->setArguments(array(new Statement('@doctrine.dao', array('Venne\Comments\CommentEntity'))))
			->addTag(SystemExtension::TAG_TRAY_COMPONENT)
			->addTag(WidgetsExtension::TAG_WIDGET, 'comments')
			->setInject(true);

		$container->addDefinition($this->prefix('chatControlFactory'))
			->setImplement('Venne\Comments\Components\IChatControlFactory')
			->setArguments(array(new Statement('@doctrine.dao', array('Venne\Comments\CommentEntity'))))
			->addTag(WidgetsExtension::TAG_WIDGET, 'chat')
			->setInject(true);

		$container->addDefinition($this->prefix('commentFormFactory'))
			->setClass('Venne\Comments\Components\CommentFormFactory', array(new Statement('@system.admin.basicFormFactory', array())));
	}

	/**
	 * @return string[]
	 */
	public function getPresenterMapping()
	{
		return array(
			'Comments' => 'Venne\Comments\*Module\*Presenter',
		);
	}

	/**
	 * @return string[]
	 */
	public function getEntityMappings()
	{
		return array(
			'Venne\Comments' => dirname(__DIR__) . '/*Entity.php',
		);
	}

}
