use g4;

INSERT INTO cliente (codigo, nome, cpf, data_nascimento, telefone, email, endereco, foto) VALUES
('C0000001', 'João Silva', '12345678900', '1985-03-12', '11912345678', 'joao.silva@email.com', 'Rua das Flores, 123 - São Paulo/SP', '/app/img/customer_person_people_man_user_client_1629.png'),
('C0000002', 'Maria Oliveira', '98765432100', '1990-07-25', '21998765432', 'maria.oliveira@email.com', 'Av. Atlântica, 456 - Rio de Janeiro/RJ', '/app/img/customer_person_people_woman_user_client_1631.png'),
('C0000003', 'Lucas Martins', '32165498700', '1993-10-02', '11944445555', 'lucas.martins@email.com','Rua Nova, 111 - Curitiba/PR', '/app/img/customer_person_people_man_user_client_1634.png'),
('C0000004', 'Aline Souza', '45612378900', '1987-08-19', '31999998888', 'alinesouza@email.com', 'Av. Central, 222 - Porto Alegre/RS', '/app/img/customer_person_people_woman_user_client_1635.png');


INSERT INTO funcionario (nome) VALUES
('Carlos Mendes'),
('Ana Paula'),
('Renato Borges'),
('Clara Menezes');

INSERT INTO item (codigo, descricao, modelo, fabricante, valorPorHora, avarias, disponibilidade, tipo) VALUES
('I0000001', 'Bicicleta Aro 29', 'MTB', 'Caloi', 15.00, 'Nenhuma', TRUE, 'bicicleta'),
('I0000002', 'Bicicleta Urbana', 'City', 'Monark', 12.00, 'Risco no quadro', FALSE, 'bicicleta'),
('I0000003', 'Capacete', 'Standard', 'Pro Tork', 5.00, 'Nenhuma', TRUE, 'equipamento'),
('I0000004', 'Kit de Ferramentas', 'Toolbox X', 'Vonder', 8.00, 'Chave faltando', TRUE, 'equipamento'),
('I0000005', 'Bicicleta Elétrica', 'E-Bike Pro', 'Sense', 25.00, 'Bateria com desgaste', TRUE, 'bicicleta'),
('I0000006', 'Bicicleta Infantil', 'Kids 16', 'Houston', 10.00, 'Manoplas gastas', TRUE, 'bicicleta'),
('I0000007', 'Patinete Elétrico', 'Scooter X', 'Xiaomi', 20.00, 'Riscos na lataria', TRUE, 'equipamento'),
('I0000008', 'Capacete Infantil', 'Mini', 'Pro Tork', 4.00, 'Correia frouxa', FALSE, 'equipamento'),
('I0000009', 'Bomba de Ar', 'AirMax', 'Giyo', 3.00, 'Bico danificado', FALSE, 'equipamento'),
('I0000010', 'Trava de Segurança', 'U-Lock', 'Kryptonite', 2.00, 'Chave reserva ausente', TRUE, 'equipamento'),
('I0000011', 'Luz Traseira LED', 'FlashBack', 'Cateye', 1.50, 'Sem avarias', TRUE, 'equipamento'),
('I0000012', 'Bicicleta Speed', 'RaceX', 'Specialized', 22.00, 'Aro levemente empenado', TRUE, 'bicicleta'),
('I0000013', 'Bicicleta Dobrável', 'Compact', 'Dahon', 18.00, 'Dobradiça com folga', FALSE, 'bicicleta'),
('I0000014', 'Suporte de Celular', 'BikeClip', 'Atrio', 1.00, 'Arranhado', TRUE, 'equipamento'),
('I0000015', 'Bicicleta Aro 26', 'Mountain Light', 'Track', 13.00, 'Pneus novos, sem avarias', TRUE, 'bicicleta'),
('I0000016', 'Capacete Premium', 'X-Pro', 'LS2', 7.00, 'Arranhões leves na lateral', TRUE, 'equipamento'),
('I0000017', 'Bicicleta Tandem', 'Duo Bike', 'Colli', 30.00, 'Assento traseiro rasgado', TRUE, 'bicicleta'),
('I0000018', 'Trava Reforçada', 'SteelLock', 'HighSecure', 3.00, 'Oxidação leve', TRUE, 'equipamento'),
('I0000019', 'Lanterna Dianteira LED', 'BrightRide', 'Oggi', 2.50, 'Suporte com folga', TRUE, 'equipamento'),
('I0000020', 'Bicicleta Gravel', 'Adventure GX', 'Cannondale', 26.00, 'Câmbio traseiro ajustado recentemente', TRUE, 'bicicleta'),
('I0000021', 'Cadeirinha Infantil', 'KidsRide', 'Thule', 4.50, 'Etiqueta desbotada', TRUE, 'equipamento'),
('I0000022', 'Bicicleta Retrô', 'Vintage Lady', 'Monark', 16.00, 'Freio traseiro com desgaste', TRUE, 'bicicleta'),
('I0000023', 'Capacete com Viseira', 'Vision Pro', 'Atrio', 6.00, 'Viseira riscada', TRUE, 'equipamento'),
('I0000024', 'Bomba de Pé', 'MaxPump 3000', 'Giyo', 3.50, 'Mangueira com pequenas trincas', TRUE, 'equipamento');

INSERT INTO bicicleta (idItem, numeroSeguro) VALUES
(1, 'SEG123456'),
(2, 'SEG654321');

INSERT INTO equipamento (idItem) VALUES
(3),
(4);

INSERT INTO locacao (entrada, numero_de_horas, desconto, valor_total, previsao_de_entrega, cliente_id, funcionario_id, ativo) VALUES
('2025-05-18 10:00:00', 4, 0.00, 60.00, '2025-05-18 14:00:00', 1, 2, 0),
('2025-05-18 12:00:00', 2, 5.00, 19.00, '2025-05-18 14:00:00', 2, 1, 1),
('2025-05-19 09:00:00', 3, 0.00, 39.00, '2025-05-19 12:00:00', 2, 2, 1),
('2025-05-21 10:00:00', 2, 0.00, 30.00, '2025-05-21 12:00:00', 2, 1, 0),
('2025-05-21 14:30:00', 3, 0.00, 36.00,'2025-05-21 17:30:00', 2, 1, 1),
('2025-06-01 09:00:00', 3, 0.00, 75.00, '2025-06-01 12:00:00', 3, 3, 1), 
('2025-06-01 13:30:00', 2, 3.00, 37.00, '2025-06-01 15:30:00', 4, 4, 1),
('2025-06-02 10:00:00', 4, 0.00, 88.00, '2025-06-02 14:00:00', 4, 3, 1),
('2025-06-03 09:00:00', 4, 0.00, 104.00, '2025-06-03 13:00:00', 3, 3, 1),  
('2025-06-03 15:00:00', 2, 2.00, 48.00, '2025-06-03 17:00:00', 4, 4, 1),  
('2025-06-04 10:30:00', 3, 0.00, 78.00, '2025-06-04 13:30:00', 1, 1, 1),  
('2025-06-05 08:00:00', 2, 0.00, 36.00, '2025-06-05 10:00:00', 2, 2, 0);  


INSERT INTO item_locacao (item_id, locacao_id, preco_locacao, subtotal) VALUES
(1, 1, 15.00, 60.00), -- 4 horas
(3, 2, 5.00, 10.00),  -- 2 horas
(4, 2, 8.00, 16.00),
(2, 3, 12.00, 36.00),  -- 3 horas
(8, 3, 5.00, 15.00),   -- 3 horas
(9, 4, 15.00, 30.00),
(13, 5, 12.00, 36.00),
(5, 6, 25.00, 75.00), 
(6, 7, 10.00, 20.00), 
(10, 7, 2.00, 4.00),  
(7, 8, 20.00, 80.00), 
(14, 8, 2.00, 8.00),
(20, 9, 26.00, 104.00),
(22, 10, 16.00, 32.00),
(18, 10, 3.00, 6.00),
(17, 11, 30.00, 90.00),
(21, 11, 4.50, 13.50),
(23, 11, 6.00, 18.00),
(15, 12, 13.00, 26.00),
(19, 12, 2.50, 5.00);

INSERT INTO devolucao (locacao_id, data_de_devolucao, valor_pago, funcionario_id) VALUES
(1, '2025-05-18 14:00:00', 60.00, 1),
(2, '2025-05-18 14:05:00', 21.00, 1);
