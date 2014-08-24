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

use Kdyby\Doctrine\EntityDao;
use Nette\Application\UI\Multiplier;
use Nette\Security\User;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CommentsControl extends \Venne\System\UI\Control
{

	/** @var \Nette\Security\User */
	private $user;

	/** @var \Kdyby\Doctrine\EntityDao */
	private $commentDao;

	/** @var \Venne\Comments\Components\IChatControlFactory */
	private $chatControlFactory;

	public function __construct(
		EntityDao $commentDao,
		User $user,
		IChatControlFactory $chatControlFactory
	)
	{
		parent::__construct();

		$this->commentDao = $commentDao;
		$this->user = $user;
		$this->chatControlFactory = $chatControlFactory;
	}

	/**
	 * @param string|null $tag
	 */
	public function handleOpenChat($tag = null)
	{
		$this->template->chat = $tag ?: 'null';
		$this->redrawControl('chat-container');
	}

	/**
	 * @return int
	 */
	public function countComments()
	{
		$ret = $this->getDql()
			->select('COUNT(a.id)')
			->getQuery()
			->getScalarResult();

		return count($ret);
	}

	/**
	 * @return \Venne\Comments\CommentEntity[]
	 */
	public function getComments()
	{
		return $this->getDql()
			->orderBy('a.created', 'DESC')
			->getQuery()
			->getResult();
	}

	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getDql()
	{
		return $this->commentDao->createQueryBuilder('a')
			->andWhere('a.recipient IS NULL OR a.recipient = :recipient')->setParameter('recipient', $this->user->identity)
			->groupBy('a.tag')
			->addGroupBy('a.recipient');
	}

	/**
	 * @return \Nette\Application\UI\Multiplier
	 */
	public function createComponentChat()
	{
		return new Multiplier(function ($tag) {
			$chat = $this->chatControlFactory->create();
			$chat->setTag($tag == 'null' ? null : $tag);

			return $chat;
		});
	}

	public function render()
	{
		$this->template->render();
	}

}

