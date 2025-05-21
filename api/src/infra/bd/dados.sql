use g4;

INSERT INTO cliente (codigo, nome, cpf, foto) VALUES
('C001', 'João Silva', '12345678900', 'https://lh3.googleusercontent.com/pw/AP1GczPDBp5n89vccP2zxg_QLB_2AzFGMAGpLrTo0gw6h0f89SoaK8Hf5aFUCiutECGc50y1c_YLxgabwfJUY7cGDrbbb52hrB7T7W6bpwnVlk5FjavOv-BNdBmxeu9Bls5oFvZjMlqNz_yYxJgc5Jd24cpB=w740-h740-s-no-gm?authuser=0'),
('C002', 'Maria Oliveira', '98765432100', 'https://lh3.googleusercontent.com/pw/AP1GczPDBp5n89vccP2zxg_QLB_2AzFGMAGpLrTo0gw6h0f89SoaK8Hf5aFUCiutECGc50y1c_YLxgabwfJUY7cGDrbbb52hrB7T7W6bpwnVlk5FjavOv-BNdBmxeu9Bls5oFvZjMlqNz_yYxJgc5Jd24cpB=w740-h740-s-no-gm?authuser=0');

INSERT INTO funcionario (nome) VALUES
('Carlos Mendes'),
('Ana Paula');

INSERT INTO item (codigo, descricao, modelo, fabricante, valorPorHora, avarias, disponibilidade, tipo) VALUES
('I0000001', 'Bicicleta Aro 29', 'MTB', 'Caloi', 15.00, 'Nenhuma', TRUE, 'bicicleta'),
('I0000002', 'Bicicleta Urbana', 'City', 'Monark', 12.00, 'Risco no quadro', TRUE, 'bicicleta'),
('I0000003', 'Capacete', 'Standard', 'Pro Tork', 5.00, 'Nenhuma', TRUE, 'equipamento'),
('I0000004', 'Kit de Ferramentas', 'Toolbox X', 'Vonder', 8.00, 'Chave faltando', TRUE, 'equipamento'),
('I0000005', 'Bicicleta Elétrica', 'E-Bike Pro', 'Sense', 25.00, 'Bateria com desgaste', TRUE, 'bicicleta'),
('I0000006', 'Bicicleta Infantil', 'Kids 16', 'Houston', 10.00, 'Manoplas gastas', TRUE, 'bicicleta'),
('I0000007', 'Patinete Elétrico', 'Scooter X', 'Xiaomi', 20.00, 'Riscos na lataria', TRUE, 'equipamento'),
('I0000008', 'Capacete Infantil', 'Mini', 'Pro Tork', 4.00, 'Correia frouxa', TRUE, 'equipamento'),
('I0000009', 'Bomba de Ar', 'AirMax', 'Giyo', 3.00, 'Bico danificado', TRUE, 'equipamento'),
('I0000010', 'Trava de Segurança', 'U-Lock', 'Kryptonite', 2.00, 'Chave reserva ausente', TRUE, 'equipamento'),
('I0000011', 'Luz Traseira LED', 'FlashBack', 'Cateye', 1.50, 'Sem avarias', TRUE, 'equipamento'),
('I0000012', 'Bicicleta Speed', 'RaceX', 'Specialized', 22.00, 'Aro levemente empenado', TRUE, 'bicicleta'),
('I0000013', 'Bicicleta Dobrável', 'Compact', 'Dahon', 18.00, 'Dobradiça com folga', TRUE, 'bicicleta'),
('I0000014', 'Suporte de Celular', 'BikeClip', 'Atrio', 1.00, 'Arranhado', TRUE, 'equipamento');

INSERT INTO bicicleta (idItem, numeroSeguro) VALUES
(1, 'SEG123456'),
(2, 'SEG654321');

INSERT INTO equipamento (idItem) VALUES
(3),
(4);

INSERT INTO locacao (entrada, numero_de_horas, desconto, valor_total, previsao_de_entrega, cliente_id, funcionario_id, ativo) VALUES
('2025-05-18 10:00:00', 4, 0.00, 60.00, '2025-05-18 14:00:00', 1, 2, 0),
('2025-05-18 12:00:00', 2, 5.00, 19.00, '2025-05-18 14:00:00', 2, 1, 0),
('2025-05-19 09:00:00', 3, 0.00, 39.00, '2025-05-19 12:00:00', 2, 2, 1);

INSERT INTO item_locacao (item_id, locacao_id, precoLocacao, subtotal) VALUES
(1, 1, 15.00, 60.00), -- 4 horas
(3, 2, 5.00, 10.00),  -- 2 horas
(4, 2, 8.00, 16.00),
(2, 3, 12.00, 36.00),  -- 3 horas
(3, 3, 5.00, 15.00);   -- 3 horas

INSERT INTO devolucao (locacao_id, data_de_devolucao, valor_pago) VALUES
(1, '2025-05-18 14:00:00', 60.00),
(2, '2025-05-18 14:05:00', 21.00);
