<?php

namespace App\Enums;

enum DivingLogSpecialityDiveEnum: string
{
    case OpenWater = 'Open Water';
    case NightDive = 'Night Dive';
    case DeepDive = 'Deep Dive';
    case NitroxDive = 'Nitrox Dive';
    case DriftDive = 'Drift Dive';
    case Snorkelling = 'Snorkelling';
    case Swimming = 'Swimming Pool';
    case WreckDive = 'Wreck Dive';
    case PhotoVideoDive = 'Photo and Video Dive';
    case Search = 'Search';
    case CaveCavernDive = 'Cave / Cavern Dive';
    case IceDive = 'Ice Dive';
    case AltitudeDive = 'Altitude Dive';
    case DPVScooter = 'DPV / Scooter';
    case Sidemount = 'Sidemount';
}
