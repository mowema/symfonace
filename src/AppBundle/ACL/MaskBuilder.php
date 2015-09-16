<?php
namespace AppBundle\ACL;

use Symfony\Component\Security\Acl\Permission\MaskBuilder as BaseMaskBuilder;


class MaskBuilder extends BaseMaskBuilder {

  const MASK_PRINT = 256; // 1 << 8
  const CODE_PRINT = 'A';   }