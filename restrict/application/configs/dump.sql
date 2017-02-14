--
-- テーブルの構造 `archive`
--

CREATE TABLE IF NOT EXISTS `archive` (
  `archiveCode` int(11) NOT NULL AUTO_INCREMENT COMMENT '主キー',
  `archiveCreate` int(11) NOT NULL COMMENT '作成時刻',
  `archiveTitle` tinytext COMMENT 'タイトル',
  `archiveBody` text COMMENT '本文',
  `archiveUrl` varchar(256) NOT NULL COMMENT '記事URL',
  `archiveImage` varchar(256) DEFAULT NULL COMMENT '画像URL',
  `archiveOrigin` int(11) NOT NULL COMMENT 'サイト主キー',
  `archiveOut` int(11) NOT NULL DEFAULT '0' COMMENT '累計OUT',
  PRIMARY KEY (`archiveCode`),
  KEY `arhiveOrigin` (`archiveOrigin`),
  KEY `archiveUrl` (`archiveUrl`(191)),
  KEY `archiveOut` (`archiveOut`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='記事マスタ' AUTO_INCREMENT=156 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `in`
--

CREATE TABLE IF NOT EXISTS `in` (
  `inCode` int(11) NOT NULL AUTO_INCREMENT COMMENT '主キー',
  `inCreate` int(11) NOT NULL COMMENT '発生時刻',
  `inReferer` varchar(256) DEFAULT NULL COMMENT 'リファラー',
  `inOrigin` int(11) DEFAULT NULL COMMENT 'サイト主キー',
  PRIMARY KEY (`inCode`),
  KEY `inOrigin` (`inOrigin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='インログ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `origin`
--

CREATE TABLE IF NOT EXISTS `origin` (
  `originCode` int(11) NOT NULL AUTO_INCREMENT COMMENT '主キー',
  `originName` varchar(256) NOT NULL COMMENT 'サイト名',
  `originUrl` varchar(256) NOT NULL COMMENT 'サイトURL',
  `originRss` varchar(256) NOT NULL COMMENT 'RSSURL',
  `originTryAt` int(11) DEFAULT NULL COMMENT '取得挑戦時刻',
  `originGetAt` int(11) DEFAULT NULL COMMENT '最終取得時刻',
  `originFailure` int(11) NOT NULL DEFAULT '0' COMMENT '失敗回数',
  `originImage` varchar(256) NOT NULL COMMENT '画像イメージURL',
  `originDomain` varchar(64) DEFAULT NULL COMMENT 'ドメイン',
  `originIn` int(11) NOT NULL DEFAULT '0' COMMENT 'ランク用IN',
  `originOut` int(11) NOT NULL DEFAULT '0' COMMENT 'ランク用OUT',
  `originActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`originCode`),
  KEY `originDomain` (`originDomain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='取得する対象サイトマスタ' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `out`
--

CREATE TABLE IF NOT EXISTS `out` (
  `outCode` int(11) NOT NULL AUTO_INCREMENT COMMENT '主キー',
  `outCreate` int(11) NOT NULL COMMENT '発生時刻',
  `outOrigin` int(11) DEFAULT NULL COMMENT 'サイト主キー',
  `outArchive` int(11) DEFAULT NULL COMMENT '記事主キー',
  PRIMARY KEY (`outCode`),
  KEY `outOrigin` (`outOrigin`),
  KEY `outArchive` (`outArchive`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='アウトログ' AUTO_INCREMENT=29 ;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `archive`
--
ALTER TABLE `archive`
  ADD CONSTRAINT `archive_ibfk_1` FOREIGN KEY (`archiveOrigin`) REFERENCES `origin` (`originCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `in`
--
ALTER TABLE `in`
  ADD CONSTRAINT `in_ibfk_1` FOREIGN KEY (`inOrigin`) REFERENCES `origin` (`originCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `out`
--
ALTER TABLE `out`
  ADD CONSTRAINT `out_ibfk_3` FOREIGN KEY (`outArchive`) REFERENCES `archive` (`archiveCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `out_ibfk_2` FOREIGN KEY (`outOrigin`) REFERENCES `origin` (`originCode`) ON DELETE CASCADE ON UPDATE CASCADE;