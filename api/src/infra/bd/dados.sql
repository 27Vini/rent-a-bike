INSERT INTO cliente (codigo, nome, cpf, foto) VALUES
('C001', 'João Silva', '12345678900', 'https://lh3.googleusercontent.com/pw/AP1GczPDBp5n89vccP2zxg_QLB_2AzFGMAGpLrTo0gw6h0f89SoaK8Hf5aFUCiutECGc50y1c_YLxgabwfJUY7cGDrbbb52hrB7T7W6bpwnVlk5FjavOv-BNdBmxeu9Bls5oFvZjMlqNz_yYxJgc5Jd24cpB=w740-h740-s-no-gm?authuser=0'),
('C002', 'Maria Oliveira', '98765432100', 'https://lh3.googleusercontent.com/pw/AP1GczPDBp5n89vccP2zxg_QLB_2AzFGMAGpLrTo0gw6h0f89SoaK8Hf5aFUCiutECGc50y1c_YLxgabwfJUY7cGDrbbb52hrB7T7W6bpwnVlk5FjavOv-BNdBmxeu9Bls5oFvZjMlqNz_yYxJgc5Jd24cpB=w740-h740-s-no-gm?authuser=0');

INSERT INTO funcionario (nome) VALUES
('Carlos Mendes'),
('Ana Paula');

INSERT INTO item (codigo, descricao, modelo, fabricante, valorPorHora, avarias, disponibilidade, tipo) VALUES
('I001', 'Bicicleta Aro 29', 'MTB', 'Caloi', 15.00, 'Nenhuma', TRUE, 'bicicleta'),
('I002', 'Bicicleta Urbana', 'City', 'Monark', 12.00, 'Risco no quadro', TRUE, 'bicicleta'),
('I003', 'Capacete', 'Standard', 'Pro Tork', 5.00, 'Nenhuma', TRUE, 'equipamento'),
('I004', 'Kit de Ferramentas', 'Toolbox X', 'Vonder', 8.00, 'Chave faltando', TRUE, 'equipamento');

INSERT INTO bicicleta (idItem, numeroSeguro) VALUES
(1, 'SEG123456'),
(2, 'SEG654321');

INSERT INTO equipamento (idItem) VALUES
(3),
(4);

INSERT INTO locacao (entrada, numero_de_horas, desconto, valor_total, previsao_de_entrega, cliente_id, funcionario_id) VALUES
('2025-05-18 10:00:00', 4, 0.00, 60.00, '2025-05-18 14:00:00', 1, 1),
('2025-05-18 12:00:00', 2, 5.00, 19.00, '2025-05-18 14:00:00', 2, 2);

INSERT INTO item_locacao (item_id, locacao_id, precoLocacao, subtotal) VALUES
(1, 1, 15.00, 60.00), -- 4 horas
(3, 2, 5.00, 10.00),  -- 2 horas
(4, 2, 8.00, 16.00);

INSERT INTO devolucao (locacao_id, data_de_devolucao, valor_pago) VALUES
(1, '2025-05-18 14:00:00', 60.00),
(2, '2025-05-18 14:05:00', 21.00);
