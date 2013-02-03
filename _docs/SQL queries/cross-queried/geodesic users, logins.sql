SELECT u.id, 
    FROM_UNIXTIME(u.date_joined) AS 'registerDate',u.username, l.password, 
    u.firstname AS 'name', u.optional_field_1 AS '_middlename', u.lastname AS '_lastname', 
    u.company_name AS '_company_name', '' AS '_country_id', u.zip AS '_zip', u.city AS '_city', 
    u.optional_field_2 AS '_street', u.optional_field_3 AS '_house_number', 
    u.optional_field_4 AS '_corpus_number', u.optional_field_5 AS '_flat_office_number', 
    u.phone AS '_phone_number', u.phone2 AS '_phone2_number', u.email
FROM auc13_geodesic_users AS u 
LEFT JOIN auc13_geodesic_logins AS l ON l.username = u.username
ORDER BY u.id DESC;