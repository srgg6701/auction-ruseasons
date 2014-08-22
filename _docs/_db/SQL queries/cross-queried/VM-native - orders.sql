SELECT o.*, 
CONCAT_WS(' ',u.first_name,u.middle_name,u.last_name) 
                AS order_name, 
u.email         AS order_email,
pm.payment_name AS payment_method  
     FROM auc13_virtuemart_orders               AS o
LEFT JOIN auc13_virtuemart_order_userinfos      AS u
          ON  u.virtuemart_order_id = o.virtuemart_order_id 
              AND u.address_type="BT"
LEFT JOIN auc13_virtuemart_paymentmethods_ru_ru AS pm
          ON  o.virtuemart_paymentmethod_id = pm.virtuemart_paymentmethod_id  
    WHERE ( o.virtuemart_vendor_id = "1" ) 