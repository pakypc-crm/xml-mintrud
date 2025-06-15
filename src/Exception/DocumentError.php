<?php

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
        public readonly CustomStudent $student,
        public readonly StudentInGroup $studentInGroup,
        public readonly studentInGroupProgEx $studentInGroupProgEx,
        public readonly CustomStudProf $position,
        public readonly EduGroup $group,
        public readonly EduProgram $program,
        public readonly Organization $organization,
    ) {
        parent::__construct(previous: $previous);
    }
}
