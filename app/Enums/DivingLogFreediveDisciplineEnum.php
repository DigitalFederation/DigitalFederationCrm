<?php

namespace App\Enums;

enum DivingLogFreediveDisciplineEnum: string
{
    case Static = 'Static';
    case DynamicNoFins = 'Dynamic no Fins';
    case DynamicFins = 'Dynamic Fins';
    case FreeImmersion = 'Free Immersion';
    case ConstantWeight = 'Constant Weight';
    case ConstantNoFins = 'Constant No Fins';
    case VariableWeight = 'Variable Weight';
    case JumpBlue = 'Jump Blue';
    case WalkingApnea = 'Walking Apnea';
}
