<?php

namespace Slinpin;

interface ProviderContext extends Provider
{
    public function applies(Container $container);
}