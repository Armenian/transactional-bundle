<?php

declare(strict_types=1);

namespace DMP\TransactionalBundle\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class Transactional
{
}
