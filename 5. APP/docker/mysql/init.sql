-- AntHive - Esquema inicial de base de datos

CREATE TABLE IF NOT EXISTS `role` (
    `id`        BIGINT      NOT NULL AUTO_INCREMENT,
    `role_name` VARCHAR(50) NOT NULL UNIQUE,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `user` (
    `id`              BIGINT       NOT NULL AUTO_INCREMENT,
    `email`           VARCHAR(255) NOT NULL UNIQUE,
    `name`            VARCHAR(100) NOT NULL,
    `surname`         VARCHAR(100) NOT NULL,
    `password`        VARCHAR(255) NOT NULL,
    `date_registered` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `avatar`          VARCHAR(255) NULL,
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

-- Datos iniciales
INSERT INTO `role` (`role_name`) VALUES ('user'), ('moderator');