<?php

declare(strict_types=1);

namespace Domain\Rdml;

use Alchemy\Zippy\Adapter\ZipAdapter;
use Alchemy\Zippy\Adapter\ZipExtensionAdapter;
use Alchemy\Zippy\FileStrategy\AbstractFileStrategy;

final class RdmlFileStrategy extends AbstractFileStrategy
{
    /**
     * {@inheritdoc}
     */
    protected function getServiceNames(): array
    {
        return [
            ZipAdapter::class,
            ZipExtensionAdapter::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFileExtension()
    {
        return 'rdml';
    }
}
