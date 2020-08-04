<?php

/**
 * Provider which registers the FAQ section to the API.
 */

namespace OWC\PDC\FAQ\RestApi;

use OWC\PDC\Base\Repositories\Item;
use OWC\PDC\Base\Foundation\ServiceProvider;

/**
 * Provider which registers the FAQ section to the API.
 */
class RestAPIServiceProvider extends ServiceProvider
{

    /**
     * Registers the faq section.
     */
    public function register()
    {
        Item::addGlobalField('faq', new FAQField($this->plugin));
    }
}
