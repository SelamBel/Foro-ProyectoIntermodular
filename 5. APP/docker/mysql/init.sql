-- AntHive - Esquema inicial de base de datos

DROP TABLE IF EXISTS publication_history, notification, message, vote, comment, publication, user_has_role, user, role;

CREATE TABLE IF NOT EXISTS `role` (
    `id`        BIGINT      NOT NULL AUTO_INCREMENT,
    `role_name` VARCHAR(50) NOT NULL UNIQUE,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `user` (
    `id`              BIGINT       NOT NULL AUTO_INCREMENT,
    `email`           VARCHAR(255) NOT NULL UNIQUE,
    `username`        VARCHAR(100) NOT NULL UNIQUE,
    `password`        VARCHAR(255) NOT NULL,
    `avatar`          VARCHAR(255) NULL,
    `date_registered` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `user_has_role` (
    `id_user` BIGINT NOT NULL,
    `id_role` BIGINT NOT NULL,
    PRIMARY KEY (`id_user`, `id_role`),
    FOREIGN KEY (`id_user`) REFERENCES `user`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_role`) REFERENCES `role`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `publication` (
    `id`            BIGINT   NOT NULL AUTO_INCREMENT,
    `id_user`       BIGINT   NOT NULL,
    `title`         VARCHAR(300) NOT NULL,
    `content`       TEXT     NOT NULL,
    `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_edited`   DATETIME NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_user`) REFERENCES `user`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `comment` (
    `id`                BIGINT   NOT NULL AUTO_INCREMENT,
    `id_publication`    BIGINT   NOT NULL,
    `id_user`           BIGINT   NOT NULL,
    `id_comment_parent` BIGINT   NULL,
    `content`           TEXT     NOT NULL,
    `date_creation`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_edited`       DATETIME NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_publication`)    REFERENCES `publication`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_user`)           REFERENCES `user`(`id`)        ON DELETE CASCADE,
    FOREIGN KEY (`id_comment_parent`) REFERENCES `comment`(`id`)     ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `vote` (
    `id`             BIGINT  NOT NULL AUTO_INCREMENT,
    `id_user`        BIGINT  NOT NULL,
    `id_publication` BIGINT  NOT NULL,
    `type`           TINYINT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_vote` (`id_user`, `id_publication`),
    FOREIGN KEY (`id_user`)        REFERENCES `user`(`id`)        ON DELETE CASCADE,
    FOREIGN KEY (`id_publication`) REFERENCES `publication`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `message` (
    `id`           BIGINT   NOT NULL AUTO_INCREMENT,
    `id_sender`    BIGINT   NOT NULL,
    `id_receiver`  BIGINT   NOT NULL,
    `content`      TEXT     NOT NULL,
    `is_read`      TINYINT  NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_sender`)   REFERENCES `user`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_receiver`) REFERENCES `user`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `notification` (
    `id`          BIGINT       NOT NULL AUTO_INCREMENT,
    `id_user`     BIGINT       NOT NULL,
    `type`        VARCHAR(50)  NOT NULL,
    `message`     VARCHAR(500) NOT NULL,
    `url`         VARCHAR(255) NULL,
    `is_read`     TINYINT      NOT NULL DEFAULT 0,
    `date_creation` DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_user`) REFERENCES `user`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `publication_history` (
    `id`           BIGINT   NOT NULL AUTO_INCREMENT,
    `id_publication` BIGINT NOT NULL,
    `title`        VARCHAR(300) NOT NULL,
    `content`      TEXT    NOT NULL,
    `date_saved`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_publication`) REFERENCES `publication`(`id`) ON DELETE CASCADE
);

INSERT INTO `role` (`role_name`) VALUES ('user'), ('moderator');

INSERT INTO `user` (`email`, `username`, `password`, `avatar`) VALUES ('ayuda@anthive.com', 'Soporte', '$2y$10$31fJ.UgTyTb4PHrNEQzptuKBVutm4.kzX0Ko31nWDZtl5OOYx1Kzm', '/assets/img/logos/favicon.svg');

INSERT INTO `user_has_role` (`id_user`, `id_role`) VALUES (LAST_INSERT_ID(), (SELECT `id` FROM `role` WHERE `role_name` = 'moderator'));
INSERT INTO `user` (`email`, `username`, `password`) VALUES ('s@s.s', 'Sel', '$2y$10$31fJ.UgTyTb4PHrNEQzptuKBVutm4.kzX0Ko31nWDZtl5OOYx1Kzm');
INSERT INTO `user_has_role` (`id_user`, `id_role`) VALUES (LAST_INSERT_ID(), (SELECT `id` FROM `role` WHERE `role_name` = 'moderator'));