<?php namespace App\ShareMeshi\Helpers;

use Carbon\Carbon;

class StatusHelper
{
    const DEFAULT_VAT = 0.12;
    const DEFAULT_LOW_THRESHOLD = 3;

    const NONE = 'none';

    const COMPANY = 'company';
    const ADMIN = 'admin';
    const FRANCHISEE = 'franchisee';
    const COORDINATOR = 'coordinator';

    const SUB_TYPE_DELIVERY = 'delivery';

    const DELETED = 'deleted';
    const ACTIVE = 'active';
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const DISABLED = 'disabled';
    const VOID = 'void';
    const RETURN = 'return';

    const MANUAL = 'manual';

    const STAFF = 'staff';
    const BRANCH = 'branch';
    const MEMBER = 'member';
    const COMPANY_STAFF = 'company_staff';
    const GUEST = 'guest';

    const ADD_STOCK = 'add_stock';
    const SUB_STOCK = 'sub_stock';
    const DELIVER_STOCK = 'deliver_stock';
    const RETURN_STOCK = 'return_stock';
    const REQUEST_STOCK = 'request_stock';

    const ADJUSTMENT_SHORT = 'adjustment_short';
    const ADJUSTMENT_SHORTOVER = 'adjustment_shortover';
    const VOID_ADJUSTMENT_SHORT = 'void_adjustment_short';
    const VOID_ADJUSTMENT_SHORTOVER = 'void_adjustment_shortover';

    const POSTIVE_ADJUSTMENT = 'positive_adjustment';
    const NEGATIVE_ADJUSTMENT = 'negative_adjustment';

    const PERMISSION_SALE = 'sales';
    const INVENTORY = 'inventory';

    const SALE = 'sale';
    const DELIVERY_SALE = 'delivery_sale';
    const FRANCHISEE_SALE = 'franchisee_sale';
    const RETURN_SALE = 'return_sale';
    const RETURN_DELIVERY_SALE = 'return_delivery_sale';
    const SHORT_SALE = 'short_sale';
    const SHORTOVER_SALE = 'shortover_sale';
    const REDEEM = 'redeem';

    const VOID_SALE = 'void_sale';
    const VOID_DELIVERY_SALE = 'void_delivery_sale';
    const VOID_FRANCHISEE_SALE = 'void_franchisee_sale';
    const VOID_RETURN = 'void_return_sale';
    const VOID_SHORT = 'void_short_sale';
    const VOID_SHORTOVER = 'void_shortover_sale';
    const VOID_REDEEM = 'void_redeem';

    const INVENTORY_GROUP = 'inventory';
    const SALE_GROUP = 'sale';

    const PRICE_RULE_SIMPLE = 'simple-discount';
    const PRICE_RULE_SPENDX = 'spend-x-get-discount';
    const PRICE_RULE_BUYX = 'buy-x-get-discount';
    const PRICE_RULE_NO_DISCOUNT = 'no-discount';
    const PRICE_RULE_SPECIAL = 'special-discount';
    const PRICE_RULE_VOUCHER = 'voucher';

    const DISCOUNT_TYPE_FIX = 'fixed';
    const DISCOUNT_TYPE_PERCENT = 'percent';

    const ALL_FLAG = 'all';

    const DELETED_AT = self::DELETED.'_at';
    const FLAG_DELETED = '_'.self::DELETED_AT.'_';

    const EVENT_REFRESH_SALE = 'event_refresh_sale';
    const EVENT_UPDATE_NOTIFICATION = 'event_update_notification';
    const EVENT_NOTIFICATION_TOAST = 'event_notification_toast';

    const INVENTORY_STATUS_SOLD_OUT = 'sold_out';
    const INVENTORY_STATUS_LOW = 'low_inventory';


    public static function flagDelete($stringVariable)
    {
        $currentTime = Carbon::now();

        $now = $currentTime->toDateTimeString();

        $nowInMicro = $currentTime->micro;

        $newStringVariable = $stringVariable.self::FLAG_DELETED.$now.$nowInMicro;

        return $newStringVariable;
    }

    public static function getNonVisibleTransactionsGroup()
    {
        return [
            StatusHelper::VOID_SHORTOVER,
            StatusHelper::VOID_SALE,
            StatusHelper::VOID_SHORT,
            StatusHelper::VOID_RETURN
        ];
    }
}