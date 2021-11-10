<?php declare(strict_types = 1);
namespace noxkiwi\core\Constants;

/**
 * Class Icon
 *
 * @package   noxkiwi\core;
 *
 * @author    Jan Nox <jan.nox@pm.me>
 *
 * @copyright 2019 noxkiwi
 * @version   1.0.0
 */
abstract class Bootstrap
{
    public const               CONTROL_LABEL         = 'control-label';
    public const               COL_LG_3              = 'col-lg-3';
    public const               TEXT_DEF              = 'text-default';
    public const               TEXT_PRI              = 'text-primary';
    public const               TEXT_SEC              = 'text-secondary';
    public const               TXT_MUTED             = 'text-secondary';
    public const               TEXT_SUC              = 'text-success';
    public const               TXT_INFO              = 'text-info';
    public const               TEXT_WRN              = 'text-warning';
    public const               TEXT_DNG              = 'text-danger';
    public const               TEXT_DRK              = 'text-dark';
    public const               TXT_COLORS            = [
        self::TEXT_DEF,
        self::TEXT_PRI,
        self::TEXT_SEC,
        self::TXT_MUTED,
        self::TEXT_SUC,
        self::TXT_INFO,
        self::TEXT_WRN,
        self::TEXT_DNG,
        self::TEXT_DRK
    ];
    public const               BTN                   = 'btn';
    public const               BTN_DEFAULT           = 'btn-default';
    public const               BTN_PRIMARY           = 'btn-primary';
    public const               BTN_SECONDARY         = 'btn-secondary';
    public const               BTN_SUCCESS           = 'btn-success';
    public const               BTN_INFO              = 'btn-info';
    public const               BTN_WARNING           = 'btn-warning';
    public const               BTN_DANGER            = 'btn-danger';
    public const               BTN_DARK              = 'btn-dark';
    public const               BTN_LIGHT             = 'btn-light';
    public const               BTN_LINK              = 'btn-link';
    public const               BTN_OUTLINE_PRIMARY   = 'btn-outline-primary';
    public const               BTN_OUTLINE_SECONDARY = 'btn-outline-secondary';
    public const               BTN_OUTLINE_SUCCESS   = 'btn-outline-success';
    public const               BTN_OUTLINE_INFO      = 'btn-outline-info';
    public const               BTN_OUTLINE_WARNING   = 'btn-outline-warning';
    public const               BTN_OUTLINE_DANGER    = 'btn-outline-danger';
    public const               BTN_OUTLINE_DARK      = 'btn-outline-dark';
    public const               BTN_OUTLINE_LIGHT     = 'btn-outline-light';
    public const               BTN_OUTLINE_LINK      = 'btn-outline-link';
    public const               BTN_STYLES            = [
        self::BTN_PRIMARY,
        self::BTN_SECONDARY,
        self::BTN_SUCCESS,
        self::BTN_INFO,
        self::BTN_WARNING,
        self::BTN_DANGER,
        self::BTN_DARK,
        self::BTN_LIGHT,
        self::BTN_LINK,
        self::BTN_OUTLINE_PRIMARY,
        self::BTN_OUTLINE_SECONDARY,
        self::BTN_OUTLINE_SUCCESS,
        self::BTN_OUTLINE_INFO,
        self::BTN_OUTLINE_WARNING,
        self::BTN_OUTLINE_DANGER,
        self::BTN_OUTLINE_DARK,
        self::BTN_OUTLINE_LIGHT,
        self::BTN_OUTLINE_LINK
    ];
    public const               BTN_LARGE             = 'btn-lg';
    public const               BTN_SMALL             = 'btn-sm';
    public const               BTN_BLOCK             = 'btn-block';
    public const               BTN_NORMAL            = '';
    public const               BTN_SIZES             = [
        self::BTN_LARGE,
        self::BTN_SMALL,
        self::BTN_NORMAL,
        self::BTN_BLOCK
    ];
    public const               FORM_GROUP            = 'form-group';
    public const               BTN_GROUP             = 'btn-group';
}
