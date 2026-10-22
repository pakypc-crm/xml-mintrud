<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Exception;

use crm\models\customStudent;
use crm\models\customStudProf;
use crm\models\eduGroup;
use crm\models\eduProgram;
use crm\models\organization;
use crm\models\studentInGroup;
use crm\models\studentInGroupProgEx;

final class DocumentError extends \Exception
{
    public function __construct(
        \Throwable $previous,
        public readonly customStudent $student,
        public readonly studentInGroup $studentInGroup,
        public readonly studentInGroupProgEx $studentInGroupProgEx,
        public readonly customStudProf $position,
        public readonly eduGroup $group,
        public readonly eduProgram $program,
        public readonly organization $organization,
    ) {
        parent::__construct(previous: $previous);
    }
}
