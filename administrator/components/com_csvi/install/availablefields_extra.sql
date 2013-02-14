/* VirtueMart Product import */
INSERT IGNORE INTO `#__csvi_available_fields` (`csvi_name`, `component_name`, `component_table`, `component`) VALUES
('skip', 'skip', 'productimport', 'com_virtuemart'),
('combine', 'combine', 'productimport', 'com_virtuemart'),
('product_discount', 'product_discount', 'productimport', 'com_virtuemart'),
('product_discount_date_start', 'product_discount_date_start', 'productimport', 'com_virtuemart'),
('product_discount_date_end', 'product_discount_date_end', 'productimport', 'com_virtuemart'),
('product_price', 'product_price', 'productimport', 'com_virtuemart'),
('shopper_group_name', 'shopper_group_name', 'productimport', 'com_virtuemart'),
('shopper_group_name_new', 'shopper_group_name_new', 'productimport', 'com_virtuemart'),
('shopper_group_name_price', 'shopper_group_name_price', 'productimport', 'com_virtuemart'),
('related_products', 'related_products', 'productimport', 'com_virtuemart'),
('category_id', 'category_id', 'productimport', 'com_virtuemart'),
('category_path', 'category_path', 'productimport', 'com_virtuemart'),
('manufacturer_name', 'manufacturer_name', 'productimport', 'com_virtuemart'),
('manufacturer_id', 'manufacturer_id', 'productimport', 'com_virtuemart'),
('price_with_tax', 'price_with_tax', 'productimport', 'com_virtuemart'),
('product_override_price', 'product_override_price', 'productimport', 'com_virtuemart'),
('override', 'override', 'productimport', 'com_virtuemart'),
('product_box', 'product_box', 'productimport', 'com_virtuemart'),
('product_delete', 'product_delete', 'productimport', 'com_virtuemart'),
('product_name', 'product_name', 'productimport', 'com_virtuemart'),
('product_s_desc', 'product_s_desc', 'productimport', 'com_virtuemart'),
('product_desc', 'product_desc', 'productimport', 'com_virtuemart'),
('metadesc', 'metadesc', 'productimport', 'com_virtuemart'),
('metakey', 'metakey', 'productimport', 'com_virtuemart'),
('customtitle', 'customtitle', 'productimport', 'com_virtuemart'),
('slug', 'slug', 'productimport', 'com_virtuemart'),
('file_url', 'file_url', 'productimport', 'com_virtuemart'),
('file_url_thumb', 'file_url_thumb', 'productimport', 'com_virtuemart'),
('file_title', 'file_title', 'productimport', 'com_virtuemart'),
('file_description', 'file_description', 'productimport', 'com_virtuemart'),
('file_meta', 'file_meta', 'productimport', 'com_virtuemart'),
('product_tax', 'product_tax', 'productimport', 'com_virtuemart'),
('product_parent_sku', 'product_parent_sku', 'productimport', 'com_virtuemart'),
('product_delete', 'product_delete', 'productimport', 'com_virtuemart'),
('product_currency', 'product_currency', 'productimport', 'com_virtuemart'),
('custom_title', 'custom_title', 'productimport', 'com_virtuemart'),
('custom_value', 'custom_value', 'productimport', 'com_virtuemart'),
('custom_price', 'custom_price', 'productimport', 'com_virtuemart'),
('custom_param', 'custom_param', 'productimport', 'com_virtuemart'),
('min_order_level', 'min_order_level', 'productimport', 'com_virtuemart'),
('max_order_level', 'max_order_level', 'productimport', 'com_virtuemart'),
('product_tax_id', 'product_tax_id', 'productimport', 'com_virtuemart'),
('product_discount_id', 'product_discount_id', 'productimport', 'com_virtuemart'),

/* VirtueMart Category import */
('skip', 'skip', 'categoryimport', 'com_virtuemart'),
('combine', 'combine', 'categoryimport', 'com_virtuemart'),
('category_path', 'category_path', 'categoryimport', 'com_virtuemart'),
('category_path_trans', 'category_path_trans', 'categoryimport', 'com_virtuemart'),
('category_name', 'category_name', 'categoryimport', 'com_virtuemart'),
('category_description', 'category_description', 'categoryimport', 'com_virtuemart'),
('metadesc', 'metadesc', 'categoryimport', 'com_virtuemart'),
('metakey', 'metakey', 'categoryimport', 'com_virtuemart'),
('customtitle', 'customtitle', 'categoryimport', 'com_virtuemart'),
('slug', 'slug', 'categoryimport', 'com_virtuemart'),
('category_delete', 'category_delete', 'categoryimport', 'com_virtuemart'),
('file_url', 'file_url', 'categoryimport', 'com_virtuemart'),
('file_url_thumb', 'file_url_thumb', 'categoryimport', 'com_virtuemart'),
('file_title', 'file_title', 'categoryimport', 'com_virtuemart'),
('file_description', 'file_description', 'categoryimport', 'com_virtuemart'),
('file_meta', 'file_meta', 'categoryimport', 'com_virtuemart'),

/* VirtueMart Manufacturer Categories import */
('skip', 'skip', 'manufacturercategoryimport', 'com_virtuemart'),
('combine', 'combine', 'manufacturercategoryimport', 'com_virtuemart'),
('mf_category_name', 'mf_category_name', 'manufacturercategoryimport', 'com_virtuemart'),
('mf_category_desc', 'mf_category_desc', 'manufacturercategoryimport', 'com_virtuemart'),
('slug', 'slug', 'manufacturercategoryimport', 'com_virtuemart'),
('mf_category_delete', 'mf_category_delete', 'manufacturercategoryimport', 'com_virtuemart'),

/* VirtueMart Manufacturer import */
('skip', 'skip', 'manufacturerimport', 'com_virtuemart'),
('combine', 'combine', 'manufacturerimport', 'com_virtuemart'),
('mf_name', 'mf_name', 'manufacturerimport', 'com_virtuemart'),
('mf_email', 'mf_email', 'manufacturerimport', 'com_virtuemart'),
('mf_desc', 'mf_desc', 'manufacturerimport', 'com_virtuemart'),
('mf_url', 'mf_url', 'manufacturerimport', 'com_virtuemart'),
('slug', 'slug', 'manufacturerimport', 'com_virtuemart'),
('mf_category_name', 'mf_category_name', 'manufacturerimport', 'com_virtuemart'),
('manufacturer_delete', 'manufacturer_delete', 'manufacturerimport', 'com_virtuemart'),

/* VirtueMart Rating import */
('skip', 'skip', 'ratingimport', 'com_virtuemart'),
('combine', 'combine', 'ratingimport', 'com_virtuemart'),
('product_sku', 'product_sku', 'ratingimport', 'com_virtuemart'),
('vote', 'vote', 'ratingimport', 'com_virtuemart'),
('username', 'username', 'ratingimport', 'com_virtuemart'),

/* VirtueMart Medias import */
('skip', 'skip', 'mediaimport', 'com_virtuemart'),
('combine', 'combine', 'mediaimport', 'com_virtuemart'),
('product_sku', 'product_sku', 'mediaimport', 'com_virtuemart'),
('media_delete', 'media_delete', 'mediaimport', 'com_virtuemart'),

/* VirtueMart Order import */
('skip', 'skip', 'orderimport', 'com_virtuemart'),
('combine', 'combine', 'orderimport', 'com_virtuemart'),
('user_currency', 'user_currency', 'orderimport', 'com_virtuemart'), /* In the format EUR */
('payment_element', 'payment_element', 'orderimport', 'com_virtuemart'),
('state_name', 'state_name', 'orderimport', 'com_virtuemart'),
('state_2_code', 'state_2_code', 'orderimport', 'com_virtuemart'),
('state_3_code', 'state_3_code', 'orderimport', 'com_virtuemart'),
('virtuemart_state_id', 'virtuemart_state_id', 'orderimport', 'com_virtuemart'),
('country_name', 'country_name', 'orderimport', 'com_virtuemart'),
('country_2_code', 'country_2_code', 'orderimport', 'com_virtuemart'),
('country_3_code', 'country_3_code', 'orderimport', 'com_virtuemart'),
('virtuemart_country_id', 'virtuemart_country_id', 'orderimport', 'com_virtuemart'),
('shipment_element', 'shipment_element', 'orderimport', 'com_virtuemart'),
('order_status_name', 'order_status_name', 'orderimport', 'com_virtuemart'),

/* VirtueMart order item import */
('skip', 'skip', 'orderitemimport', 'com_virtuemart'),
('combine', 'combine', 'orderitemimport', 'com_virtuemart'),
('product_sku', 'product_sku', 'orderitemimport', 'com_virtuemart'),
('order_status_name', 'order_status_name', 'orderitemimport', 'com_virtuemart'),

/* VirtueMart shopperfield import */
('skip', 'skip', 'shopperfieldimport', 'com_virtuemart'),
('combine', 'combine', 'shopperfieldimport', 'com_virtuemart'),
('shopperfield_delete', 'shopperfield_delete', 'shopperfieldimport', 'com_virtuemart'),

/* VirtueMart Userinfo import */
('skip', 'skip', 'userinfoimport', 'com_virtuemart'),
('combine', 'combine', 'userinfoimport', 'com_virtuemart'),
('vendor_name', 'vendor_name', 'userinfoimport', 'com_virtuemart'),
('shopper_group_name', 'shopper_group_name', 'userinfoimport', 'com_virtuemart'),
('address_type', 'address_type', 'userinfoimport', 'com_virtuemart'),
('address_type_name', 'address_type_name', 'userinfoimport', 'com_virtuemart'),
('usergroup_name', 'usergroup_name', 'userinfoimport', 'com_virtuemart'),

/* VirtueMart waiting users import */
('skip', 'skip', 'waitinglistimport', 'com_virtuemart'),
('combine', 'combine', 'waitinglistimport', 'com_virtuemart'),
('product_sku', 'product_sku', 'waitinglistimport', 'com_virtuemart'),
('username', 'username', 'waitinglistimport', 'com_virtuemart'),

/* VirtueMart custom field import */
('skip', 'skip', 'customfieldimport', 'com_virtuemart'),
('combine', 'combine', 'customfieldimport', 'com_virtuemart'),
('plugin_name', 'plugin_name', 'customfieldimport', 'com_virtuemart'),
('vendor_name', 'vendor_name', 'customfieldimport', 'com_virtuemart'),

/* VirtueMart calculation rule import */
('skip', 'skip', 'calcimport', 'com_virtuemart'),
('combine', 'combine', 'calcimport', 'com_virtuemart'),
('currency_code_3', 'currency_code_3', 'calcimport', 'com_virtuemart'),
('category_path', 'category_path', 'calcimport', 'com_virtuemart'),
('shopper_group_name', 'shopper_group_name', 'calcimport', 'com_virtuemart'),
('country_name', 'country_name', 'calcimport', 'com_virtuemart'),
('country_2_code', 'country_2_code', 'calcimport', 'com_virtuemart'),
('country_3_code', 'country_3_code', 'calcimport', 'com_virtuemart'),
('state_name', 'state_name', 'calcimport', 'com_virtuemart'),
('state_2_code', 'state_2_code', 'calcimport', 'com_virtuemart'),
('state_3_code', 'state_3_code', 'calcimport', 'com_virtuemart'),

/* VirtueMart category export */
('category_path', 'category_path', 'categoryexport', 'com_virtuemart'),
('category_name', 'category_name', 'categoryexport', 'com_virtuemart'),
('category_description', 'category_description', 'categoryexport', 'com_virtuemart'),
('metadesc', 'metadesc', 'categoryexport', 'com_virtuemart'),
('metakey', 'metakey', 'categoryexport', 'com_virtuemart'),
('customtitle', 'customtitle', 'categoryexport', 'com_virtuemart'),
('slug', 'slug', 'categoryexport', 'com_virtuemart'),
('file_url', 'file_url', 'categoryexport', 'com_virtuemart'),
('file_url_thumb', 'file_url_thumb', 'categoryexport', 'com_virtuemart'),

/* VirtueMart calculation rule export */
('currency_code_3', 'currency_code_3', 'calcexport', 'com_virtuemart'),
('category_path', 'category_path', 'calcexport', 'com_virtuemart'),
('shopper_group_name', 'shopper_group_name', 'calcexport', 'com_virtuemart'),
('country_name', 'country_name', 'calcexport', 'com_virtuemart'),
('country_2_code', 'country_2_code', 'calcexport', 'com_virtuemart'),
('country_3_code', 'country_3_code', 'calcexport', 'com_virtuemart'),
('state_name', 'state_name', 'calcexport', 'com_virtuemart'),
('state_2_code', 'state_2_code', 'calcexport', 'com_virtuemart'),
('state_3_code', 'state_3_code', 'calcexport', 'com_virtuemart'),

/* VirtueMart Manufacturer export */
('mf_name', 'mf_name', 'manufacturerexport', 'com_virtuemart'),
('mf_email', 'mf_email', 'manufacturerexport', 'com_virtuemart'),
('mf_desc', 'mf_desc', 'manufacturerexport', 'com_virtuemart'),
('mf_url', 'mf_url', 'manufacturerexport', 'com_virtuemart'),
('slug', 'slug', 'manufacturerexport', 'com_virtuemart'),
('mf_category_name', 'mf_category_name', 'manufacturerexport', 'com_virtuemart'),

/* VirtueMart medias export */
('product_sku', 'product_sku', 'mediaexport', 'com_virtuemart'),

/* VirtueMart order export */
('custom', 'custom', 'orderexport', 'com_virtuemart'),
('user_currency', 'user_currency', 'orderexport', 'com_virtuemart'), /* In the format EUR */
('payment_element', 'payment_element', 'orderexport', 'com_virtuemart'),
('state_name', 'state_name', 'orderexport', 'com_virtuemart'),
('state_2_code', 'state_2_code', 'orderexport', 'com_virtuemart'),
('state_3_code', 'state_3_code', 'orderexport', 'com_virtuemart'),
('virtuemart_state_id', 'virtuemart_state_id', 'orderexport', 'com_virtuemart'),
('country_name', 'country_name', 'orderexport', 'com_virtuemart'),
('country_2_code', 'country_2_code', 'orderexport', 'com_virtuemart'),
('country_3_code', 'country_3_code', 'orderexport', 'com_virtuemart'),
('virtuemart_country_id', 'virtuemart_country_id', 'orderexport', 'com_virtuemart'),
('shipment_element', 'shipment_element', 'orderexport', 'com_virtuemart'),
('order_status_name', 'order_status_name', 'orderexport', 'com_virtuemart'),
('full_name', 'full_name', 'orderexport', 'com_virtuemart'),
('username', 'username', 'orderexport', 'com_virtuemart'),
('total_order_items', 'total_order_items', 'orderexport', 'com_virtuemart'),
('discount_percentage', 'discount_percentage', 'orderexport', 'com_virtuemart'),
('product_price_total', 'product_price_total', 'orderexport', 'com_virtuemart'),

/* VirtueMart order item export */
('full_name', 'fullname', 'orderitemexport', 'com_virtuemart'),
('product_sku', 'product_sku', 'orderitemexport', 'com_virtuemart'),
('order_status_name', 'order_status_name', 'orderitemexport', 'com_virtuemart'),

/* VirtueMart Product export */
('custom', 'custom', 'productexport', 'com_virtuemart'),
('product_price', 'product_price', 'productexport', 'com_virtuemart'),
('shopper_group_name', 'shopper_group_name', 'productexport', 'com_virtuemart'),
('shopper_group_name_price', 'shopper_group_name_price', 'productexport', 'com_virtuemart'),
('related_products', 'related_products', 'productexport', 'com_virtuemart'),
('category_id', 'category_id', 'productexport', 'com_virtuemart'),
('category_path', 'category_path', 'productexport', 'com_virtuemart'),
('product_box', 'product_box', 'productexport', 'com_virtuemart'),
('product_parent_sku', 'product_parent_sku', 'productexport', 'com_virtuemart'),
('product_name', 'product_name', 'productexport', 'com_virtuemart'),
('product_s_desc', 'product_s_desc', 'productexport', 'com_virtuemart'),
('product_desc', 'product_desc', 'productexport', 'com_virtuemart'),
('metadesc', 'metadesc', 'productexport', 'com_virtuemart'),
('metakey', 'metakey', 'productexport', 'com_virtuemart'),
('customtitle', 'customtitle', 'productexport', 'com_virtuemart'),
('slug', 'slug', 'productexport', 'com_virtuemart'),
('picture_url', 'picture_url', 'productexport', 'com_virtuemart'),
('override', 'override', 'productexport', 'com_virtuemart'),
('product_override_price', 'product_override_price', 'productexport', 'com_virtuemart'),
('product_currency', 'product_currency', 'productexport', 'com_virtuemart'),
('custom_shipping', 'custom_shipping', 'productexport', 'com_virtuemart'),
('basepricewithtax', 'basepricewithtax', 'productexport', 'com_virtuemart'),
('discountedpricewithouttax', 'discountedpricewithouttax', 'productexport', 'com_virtuemart'),
('pricebeforetax', 'pricebeforetax', 'productexport', 'com_virtuemart'),
('salesprice', 'salesprice', 'productexport', 'com_virtuemart'),
('taxamount', 'taxamount', 'productexport', 'com_virtuemart'),
('discountamount', 'discountamount', 'productexport', 'com_virtuemart'),
('pricewithouttax', 'pricewithouttax', 'productexport', 'com_virtuemart'),
('manufacturer_name', 'manufacturer_name', 'productexport', 'com_virtuemart'),
('custom_title', 'custom_title', 'productexport', 'com_virtuemart'),
('custom_value', 'custom_value', 'productexport', 'com_virtuemart'),
('custom_price', 'custom_price', 'productexport', 'com_virtuemart'),
('custom_param', 'custom_param', 'productexport', 'com_virtuemart'),
('file_url', 'file_url', 'productexport', 'com_virtuemart'),
('file_url_thumb', 'file_url_thumb', 'productexport', 'com_virtuemart'),
('min_order_level', 'min_order_level', 'productexport', 'com_virtuemart'),
('max_order_level', 'max_order_level', 'productexport', 'com_virtuemart'),

/* VirtueMart Rating export */
('custom', 'custom', 'ratingexport', 'com_virtuemart'),
('product_sku', 'product_sku', 'ratingexport', 'com_virtuemart'),
('vote', 'vote', 'ratingexport', 'com_virtuemart'),
('username', 'username', 'ratingexport', 'com_virtuemart'),

/* VirtueMart Shopperfield export */
('custom', 'custom', 'shopperfieldexport', 'com_virtuemart'),

/* VirtueMart Userinfo export */
('custom', 'custom', 'userinfoexport', 'com_virtuemart'),
('full_name', 'fullname', 'userinfoexport', 'com_virtuemart'),
('vendor_name', 'vendor_name', 'userinfoexport', 'com_virtuemart'),
('shopper_group_name', 'shopper_group_name', 'userinfoexport', 'com_virtuemart'),
('address_type', 'address_type', 'userinfoexport', 'com_virtuemart'),
('address_type_name', 'address_type_name', 'userinfoexport', 'com_virtuemart'),
('usergroup_name', 'usergroup_name', 'userinfoexport', 'com_virtuemart'),
('country_name', 'country_name', 'userinfoexport', 'com_virtuemart'),
('country_2_code', 'country_2_code', 'userinfoexport', 'com_virtuemart'),
('country_3_code', 'country_3_code', 'userinfoexport', 'com_virtuemart'),
('state_name', 'state_name', 'userinfoexport', 'com_virtuemart'),
('state_2_code', 'state_2_code', 'userinfoexport', 'com_virtuemart'),
('state_3_code', 'state_3_code', 'userinfoexport', 'com_virtuemart'),

/* VirtueMart Waiting users export */
('custom', 'custom', 'waitinglistexport', 'com_virtuemart'),
('product_sku', 'product_sku', 'waitinglistexport', 'com_virtuemart'),
('username', 'username', 'waitinglistexport', 'com_virtuemart'),

/* VirtueMart Custom field export */
('custom', 'custom', 'customfieldexport', 'com_virtuemart'),
('plugin_name', 'plugin_name', 'customfieldexport', 'com_virtuemart'),
('vendor_name', 'vendor_name', 'customfieldexport', 'com_virtuemart'),

/* Akeeba Subscriptions subscription export */
('custom', 'custom', 'subscriptionexport', 'com_akeebasubs'),
('name', 'name', 'subscriptionexport', 'com_akeebasubs'),
('username', 'username', 'subscriptionexport', 'com_akeebasubs'),
('email', 'email', 'subscriptionexport', 'com_akeebasubs'),
('password', 'password', 'subscriptionexport', 'com_akeebasubs'),

/* Akeeba Subscriptions affiliate export */
('custom', 'custom', 'affiliateexport', 'com_akeebasubs'),
('money_owed', 'money_owed', 'affiliateexport', 'com_akeebasubs'),
('money_paid', 'money_paid', 'affiliateexport', 'com_akeebasubs'),
('total_commission', 'total_commission', 'affiliateexport', 'com_akeebasubs'),

/* Akeeba Subscriptions coupon export */
('custom', 'custom', 'couponexport', 'com_akeebasubs'),
('name', 'name', 'couponexport', 'com_akeebasubs'),
('username', 'username', 'couponexport', 'com_akeebasubs'),
('email', 'email', 'couponexport', 'com_akeebasubs'),
('password', 'password', 'couponexport', 'com_akeebasubs'),

/* Akeeba Subscriptions coupon import */
('skip', 'skip', 'couponimport', 'com_akeebasubs'),
('username', 'username', 'couponimport', 'com_akeebasubs'),
('subscription_title', 'subscription_title', 'couponimport', 'com_akeebasubs'), /* Comma separated value */

/* Akeeba Subscriptions subscription import */
('skip', 'skip', 'subscriptionimport', 'com_akeebasubs'),
('subscription_delete', 'subscription_delete', 'subscriptionimport', 'com_akeebasubs'),
('subscription_title', 'subscription_title', 'subscriptionimport', 'com_akeebasubs'),
('name', 'name', 'subscriptionimport', 'com_akeebasubs'),
('username', 'username', 'subscriptionimport', 'com_akeebasubs'),
('email', 'email', 'subscriptionimport', 'com_akeebasubs'),
('password', 'password', 'subscriptionimport', 'com_akeebasubs'),

/* Akeeba Subscriptions affiliate import */
('skip', 'skip', 'affiliateimport', 'com_akeebasubs'),
('affiliate_delete', 'affiliate_delete', 'affiliateimport', 'com_akeebasubs'),
('username', 'username', 'affiliateimport', 'com_akeebasubs'),
('amount', 'amount', 'affiliateimport', 'com_akeebasubs');