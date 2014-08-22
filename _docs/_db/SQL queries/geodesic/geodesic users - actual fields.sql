SELECT u.id, u.username, u.firstname, u.optional_field_1 AS '*middlename', u.lastname, 
    u.company_name, 'country' AS '+COUNTRY', u.zip, u.city, 
    u.optional_field_2 AS '*street', u.optional_field_3 AS '*house number', 
    u.optional_field_4 AS '*corpus', u.optional_field_5 AS '*flat/office', 
    u.phone, u.phone2, u.email, 'pass' AS '+PASSWORD'
FROM auc13_geodesic_users AS u ORDER BY u.id DESC;