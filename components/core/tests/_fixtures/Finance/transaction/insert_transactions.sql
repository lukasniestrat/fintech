INSERT INTO transaction
    (id, bank_account_id, name, subject, amount, booking_date, iban, category_id)
VALUES
    (1, 1, 'EWE Stromrechnung', 'EWE GmbH & Co. KG', -13.99, '2020-10-12', 'DE1234567890', 1),
    (2, 1, 'Netflix Monatsabo', 'Netflix Inc.', -9.99, '2020-10-13', 'DE1234567890', 1),
    (3, 1, 'Telekom Handyrechnung', 'Telekom AG', -29.95, '2020-10-13', 'DE1234567890', 1),
    (4, 1, 'Edeka Einkauf', 'Edeka GmbH', -54.62, '2020-10-13', 'DE1234567890', 1),
    (5, 1, 'Apple Store', 'Apple Inc.', -1099.00, '2020-10-13', 'DE1234567890', 1);