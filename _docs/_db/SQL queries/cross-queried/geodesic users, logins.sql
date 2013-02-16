SELECT u.id, 
    FROM_UNIXTIME(u.date_joined) AS  'registerDate',
									u.username, 
              u.email,
									l.password, 
    				  u.firstname AS 'name', 
			   u.optional_field_1 AS 'middlename', 
					   u.lastname AS 'lastname', 
    			   u.company_name AS 'company_name', 
							   0 AS 'country_id', 
							u.zip AS 'zip', 
						   u.city AS 'city', 
    		   u.optional_field_2 AS 'street', 
			   u.optional_field_3 AS 'house_number', 
    		   u.optional_field_4 AS 'corpus_number', 
			   u.optional_field_5 AS 'flat_office_number', 
    					  u.phone AS 'phone_number', 
						 u.phone2 AS 'phone2_number'
FROM auc13_geodesic_users_backup AS u 
LEFT JOIN auc13_geodesic_logins AS l ON l.username = u.username
ORDER BY u.id DESC;