<?php

namespace App\Presenters;

use App\Model\Facade\OAuthFacade;
use Drahak\Restful\Application\UI\ResourcePresenter;
use Drahak\Restful\InvalidStateException;
use Drahak\Restful\Resource\Link;
use Nette;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends ResourcePresenter
{
    /** @var  int */
    protected $userId;
    /** @var  int */
    protected $clientId;

    /** @var  Nette\Utils\Paginator */
    protected $paginator;

    /** @var  int */
    protected $actualPage;

    /** @var  OAuthFacade */
    protected $oAuthFacade;

    public function injectOAuthFacade(OAuthFacade $OAuthFacade)
    {
        $this->oAuthFacade = $OAuthFacade;
    }

    /**
     * @return Nette\Utils\Paginator
     * @throws \Drahak\Restful\InvalidStateException
     */
    protected function initPaginator()
    {
        if (!$this->paginator) {
            $this->paginator = new Nette\Utils\Paginator();
            $perPage = $this->context->parameters['collection']['perPage'];
            $this->paginator->setItemsPerPage($perPage);
        }

        $page = isset($this->input->page) ? (int) $this->input->page : 1;

        if ($page < 1) {
            throw new InvalidStateException('To create paginator page query parameter to request URL');
        }

        $this->paginator->setPage($page);

        return $this->paginator;
    }

    protected function setPaginationToHeader()
    {
        $count = $this->paginator->getItemCount();
        $page = $this->paginator->getPage();

        $this->getHttpResponse()->addHeader('X-Total-Count', $count);
        if (!$this->paginator->last) {
            $url = $this->getHttpRequest()->getUrl();
            parse_str($url->getQuery(), $query);
            $query['page'] = ++$page;
            $url->appendQuery($query);
            $link = new Link($url, Link::NEXT);
            $this->getHttpResponse()->addHeader('Link', $link);
        }
    }
}
