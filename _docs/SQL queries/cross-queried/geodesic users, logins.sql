SELECT u.id, 
    FROM_UNIXTIME(u.date_joined) AS 'registration date',u.username, l.password, 
    u.firstname, u.optional_field_1 AS '*middlename', u.lastname, 
    u.company_name, 'send to admin via email' AS '[COUNTRY]', u.zip, u.city, 
    u.optional_field_2 AS '*street', u.optional_field_3 AS '*house number', 
    u.optional_field_4 AS '*corpus', u.optional_field_5 AS '*flat/office', 
    u.phone, u.phone2, u.email
FROM auc13_geodesic_users AS u 
LEFT JOIN auc13_geodesic_logins AS l ON l.username = u.username
ORDER BY u.id DESC;