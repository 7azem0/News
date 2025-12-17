-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: News
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `language` varchar(10) DEFAULT 'en',
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `scheduled_at` datetime DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `visibility` enum('public','subscribed') DEFAULT 'public',
  `tags` text,
  `required_level` int DEFAULT '0',
  `required_plan_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (2,'How AI-Powered Translation is Transforming Local News','For decades, local newspapers have struggled with one simple problem: language. Even when a city is multilingual, most local outlets only publish in a single language, leaving large parts of the population without easy access to important information about politics, schools, transportation and public safety. Recent advances in AI translation are starting to change that.\r\n\r\nModern neural translation models are far more accurate than the rulebased systems used in the early 2000s. Instead of translating word by word, they learn patterns from millions of sentences, which helps them better capture context, idioms and tone. For local newsrooms, this means they can translate long articles in seconds and then have an editor quickly review the output, instead of paying for a full human translation from scratch.\r\n\r\nHowever, AI translation is not a magic solution. Editors still need to check names, numbers and culturally sensitive phrases. Certain types of content, such as opinion pieces or satire, often require more careful rewriting to avoid misunderstandings. Many newsrooms are adopting a hybrid workflow: AI produces a first draft, and bilingual journalists or freelancers refine the text before publication.\r\n\r\nDespite these limitations, the impact on reach is already visible. In several pilot projects, local outlets that introduced translated versions of their most important stories reported a significant increase in readership from immigrant communities who were previously underserved. Some cities are also experimenting with realtime translation of emergency alerts, making sure that critical information about extreme weather or health risks is available in multiple languages within minutes.\r\n\r\nThe next challenge will be building sustainable business models around these tools. Translation creates more value for readers, but it also increases editorial workload and infrastructure costs. Grants, public funding and subscriptionbased translation services are all being discussed as possible solutions. What is clear is that AIpowered translation is no longer a futuristic experiment  it is quickly becoming a core part of how local newsrooms can serve their entire community.','Newsroom Staff','technology','en','/Assets/Images/ai-translation-transforming-local-news.jpg','2025-12-03 12:00:00','published',NULL,0,'public','Tech,Internet,AI',0,NULL),(3,'Behind the Paywall: Why Local News Needs Subscribers to Survive','For many years, local newspapers relied almost entirely on advertising. Classifieds, print ads and inserts paid for the reporters who attended school board meetings, followed city budgets and covered high school sports. That model collapsed when ad dollars shifted to large digital platforms, leaving local outlets with shrinking newsrooms and growing coverage gaps.\r\n\r\nPaywalls and digital subscriptions are one way newsrooms are trying to rebuild a sustainable business. Instead of treating the website as a free giveaway, they present journalism as a service worth paying forjust like streaming video or music. Readers who subscribe are not simply buying access to stories; they are effectively funding the reporting itself.\r\n\r\nOf course, convincing people to pay is not easy. Many readers have become used to free content and are already paying for several entertainment subscriptions. Successful local outlets are responding with membership-style offers that include newsletters, live events, and direct ways to talk with reporters. The more a community sees its news outlet as a shared civic resource rather than just a website, the more willing people are to support it.\r\n\r\nThe longterm future of local news will likely be a mix of subscriptions, philanthropy, and limited advertising. But paywalls are playing a central role in this transition, pushing newsrooms to focus on depth, quality and trust instead of chasing page views.','Newsroom Staff','technology','en','/Assets/Images/behind-the-paywall.jpg','2025-12-03 18:01:11','published',NULL,1,'public','',0,NULL),(4,'From Print to Podcast: How Newsrooms Are Repackaging Their Best Stories','The daily newspaper used to arrive only in one form: ink on paper. Today, many of the same newsrooms are experimenting with audio series, minidocumentaries and social clips that remix their reporting for new audiences. The goal is simplemeet people where they are, whether that is on a commute, at the gym or scrolling on a phone.\r\n\r\nTurning a long investigative article into a podcast, for example, allows reporters to bring in interview clips, ambient sound and narration that can make complex topics feel more personal. Some outlets are even building small audio studios inside their offices so that journalists can record updates as stories develop.\r\n\r\nThis shift does not mean that written articles are disappearing. Instead, they form the backbone of a multiformat strategy. A single reporting project might generate a feature article, an explainer video, a podcast episode and a short social post summarizing the key finding. When all of these formats link back to each other, readers can choose how deep they want to go.\r\n\r\nFor local newsrooms, the biggest challenge is capacity. Producing quality audio and video requires new skills and equipment. Many are partnering with universities or independent producers to share expertise while keeping editorial control inhouse.','Newsroom Staff','technology','en','/Assets/Images/print-to-podcast.jpg','2025-12-03 18:01:11','published',NULL,0,'public','',0,4),(5,'Newsletters as the New Front Page','Open your email in the morning and you will likely find at least one curated newsletter summarizing the day\'s top stories. What began as a simple distribution channel has become one of the most important tools for newsrooms. Newsletters create a direct relationship with readers that is not filtered by algorithms or social media feeds.\r\n\r\nFor local outlets, this relationship is especially valuable. A welldesigned morning briefing can highlight city council decisions, weather alerts, school updates and community events in just a few paragraphs. Readers who rely on the newsletter for orientation are more likely to click through to full articles and, ultimately, to become subscribers.\r\n\r\nSuccessful newsletters balance utility and personality. They provide clear links and summaries, but they also have a recognizable voice. Many editors include short notes explaining why a story matters or how it was reported. That transparency helps build trust at a time when misinformation is easy to spread.\r\n\r\nAs inboxes become more crowded, the pressure to deliver value in every edition will only grow. Newsrooms that treat their newsletters as a core productrather than an afterthoughtare already seeing higher engagement and stronger reader loyalty.','Newsroom Staff','technology','en','/Assets/Images/newsletters-front-page.jpg','2025-12-03 18:01:11','published',NULL,0,'public','',0,NULL),(7,'The Silent Revolution: How Edge Computing Is Reshaping the Internet','For years, cloud computing has been the backbone of modern digital services. From streaming platforms to enterprise software, centralized data centers have powered everything behind the scenes. But as applications demand faster responses and real-time intelligence, a new paradigm is quietly taking over: edge computing.\r\n\r\nWhat Is Edge Computing?\r\n\r\nEdge computing shifts data processing closer to where it is generated—on devices such as smartphones, sensors, vehicles, and local servers—rather than sending everything to distant cloud data centers. This proximity dramatically reduces latency, improves reliability, and lowers bandwidth costs.\r\n\r\nInstead of asking “Can the cloud handle this?”, engineers are now asking “Does this data even need to leave the device?”\r\n\r\nWhy Edge Computing Matters Now\r\n\r\nSeveral technological shifts have made edge computing not just useful, but necessary:\r\n\r\n5G Networks enable ultra-low latency communication\r\n\r\nIoT Explosion has introduced billions of connected devices\r\n\r\nAI at the Edge allows on-device decision-making\r\n\r\nPrivacy Regulations restrict unnecessary data transfers\r\n\r\nFor applications like autonomous driving, industrial automation, or remote healthcare, milliseconds matter—and the cloud alone is often too slow.\r\n\r\nReal-World Applications\r\nSmart Cities\r\n\r\nTraffic lights that adapt in real time, surveillance systems that detect anomalies instantly, and energy grids that self-optimize all rely on edge computing.\r\n\r\nHealthcare\r\n\r\nWearable devices can analyze patient data locally, triggering alerts without waiting for cloud processing—potentially saving lives.\r\n\r\nContent Delivery & Media\r\n\r\nEdge servers reduce buffering, improve streaming quality, and personalize content delivery closer to the user.\r\n\r\nEdge vs Cloud: Not a Replacement\r\n\r\nDespite common misconceptions, edge computing is not replacing the cloud. Instead, it complements it.\r\n\r\nEdge handles real-time processing and filtering\r\n\r\nCloud manages long-term storage, analytics, and orchestration\r\n\r\nThe future belongs to hybrid architectures where intelligence is distributed across devices, edge nodes, and centralized clouds.\r\n\r\nSecurity at the Edge\r\n\r\nProcessing data locally reduces exposure, but it also introduces new security challenges. Edge devices are often physically accessible and widely distributed, making them harder to secure uniformly. Encryption, device authentication, and secure firmware updates are now critical components of edge infrastructure.\r\n\r\nThe Road Ahead\r\n\r\nAs AI models become smaller and more efficient, expect more intelligence to move away from centralized systems. Edge computing will play a key role in:\r\n\r\nAutonomous systems\r\n\r\nPersonalized digital experiences\r\n\r\nReal-time analytics\r\n\r\nSustainable computing by reducing data transfer\r\n\r\nThe internet is no longer just centralized—it’s becoming everywhere.','Hazem','technology','en','Assets/Uploads/1765993248_downl453435676876oad.jpg','2025-12-17 17:40:48','published',NULL,0,'public','Tech,Internet,Edge,Google',0,5);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `article_id` int NOT NULL,
  `content` text NOT NULL,
  `status` enum('pending','approved','flagged') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issues`
--

DROP TABLE IF EXISTS `issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `issues` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `pdf_path` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issues`
--

LOCK TABLES `issues` WRITE;
/*!40000 ALTER TABLE `issues` DISABLE KEYS */;
/*!40000 ALTER TABLE `issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int NOT NULL,
  `features` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `level` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plans`
--

LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;
INSERT INTO `plans` VALUES (2,'Basic',9.99,30,'Access to basic articles\nArabic/English translation\nEmail newsletters\nMobile app access','2025-12-17 19:11:29',1),(3,'Plus',19.99,30,'Access to premium articles\nText-to-Speech (TTS) feature\nOffline reading\nAdvanced search\nEverything in Basic','2025-12-17 19:11:29',2),(4,'Pro',29.99,30,'All languages translation\nPriority customer support\nUnlimited article saves\nCustom news feeds\nEverything in Plus','2025-12-17 19:11:29',3),(5,'Sunless',49.51,30,'Forth Wall','2025-12-17 19:14:08',1);
/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saved_articles`
--

DROP TABLE IF EXISTS `saved_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `saved_articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `article_id` int NOT NULL,
  `saved_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`article_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `saved_articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `saved_articles_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saved_articles`
--

LOCK TABLES `saved_articles` WRITE;
/*!40000 ALTER TABLE `saved_articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `saved_articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `plan` varchar(50) DEFAULT NULL,
  `auto_renew` tinyint(1) DEFAULT '1',
  `expires_at` datetime DEFAULT NULL,
  `plan_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (1,1,'Plus',0,'2025-12-17 19:22:22',NULL),(2,1,'Pro',0,'2025-12-17 19:22:22',NULL),(3,1,'Basic',0,'2025-12-17 19:22:22',NULL),(4,1,'Plus',0,'2025-12-17 19:22:22',NULL),(5,1,'Pro',0,'2025-12-17 19:22:22',NULL),(6,1,'Plus',0,'2025-12-17 19:22:22',NULL),(7,1,'Pro',0,'2025-12-17 19:22:22',NULL),(8,1,'Plus',0,'2025-12-17 19:22:22',NULL),(9,1,'Basic',0,'2025-12-17 19:22:22',NULL),(10,1,'Plus',0,'2025-12-17 19:22:22',NULL),(11,1,'Plus',0,'2025-12-17 19:22:22',NULL),(12,1,'Plus',0,'2025-12-17 19:22:22',NULL),(13,1,'Basic',0,'2025-12-17 19:22:22',NULL),(14,1,'Basic',0,'2025-12-17 19:22:22',NULL),(15,1,'Basic',0,'2025-12-17 19:22:22',NULL),(16,1,'Plus',0,'2025-12-17 19:22:22',NULL),(17,1,'Pro',0,'2025-12-17 19:22:22',NULL),(18,1,'Plus',0,'2025-12-17 19:22:22',NULL),(19,1,'Pro',0,'2025-12-17 19:22:22',NULL),(20,1,'Plus',0,'2025-12-17 19:22:22',NULL),(21,1,'Pro',0,'2025-12-17 19:22:22',NULL),(22,1,'Plus',0,'2025-12-17 19:22:22',NULL),(23,1,'Basic',0,'2025-12-17 19:22:22',NULL),(24,1,'Pro',0,'2025-12-17 19:22:22',NULL),(25,1,'Plus',0,'2025-12-17 19:22:22',NULL),(26,1,'Basic',0,'2025-12-17 19:22:22',NULL),(27,1,'Plus',0,'2025-12-17 19:22:22',NULL),(28,1,'Basic',0,'2025-12-17 19:22:22',NULL),(29,1,'Plus',0,'2025-12-17 19:22:22',NULL),(30,1,'Basic',0,'2025-12-17 19:22:22',NULL),(31,1,'Plus',0,'2025-12-17 19:22:22',NULL),(32,1,'Pro',0,'2025-12-17 19:22:22',NULL),(33,1,'Plus',0,'2025-12-17 19:22:22',NULL),(34,1,'Basic',0,'2025-12-17 19:22:22',NULL),(35,1,'Pro',0,'2025-12-17 19:22:22',NULL),(36,1,'Pro',0,'2025-12-17 19:22:22',NULL),(37,1,'Plus',0,'2025-12-17 19:22:22',NULL),(38,1,'Pro',0,'2025-12-17 19:22:22',NULL),(39,1,'Plus',0,'2025-12-17 19:22:22',NULL),(40,1,'Test',0,'2025-12-17 19:22:22',1),(41,1,'Plus',0,'2025-12-17 19:22:22',NULL),(42,7,'Plus',1,'2026-01-17 18:24:03',NULL),(43,1,'Basic',0,'2025-12-17 19:22:22',NULL),(44,1,'Plus',0,'2025-12-17 19:22:22',3),(45,1,'Pro',0,'2025-12-17 19:22:22',4),(46,1,'Basic',0,'2025-12-17 19:22:22',2),(47,1,'Pro',0,'2025-12-17 19:22:22',4),(48,1,'Sunless',0,'2025-12-17 19:22:22',5),(49,1,'Plus',0,'2025-12-17 19:22:22',3),(50,1,'Basic',0,'2025-12-17 19:22:22',2),(51,1,'Basic',1,'2026-01-16 19:33:50',2),(52,1,'Pro',1,'2026-01-16 19:40:25',4);
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `translations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `language` varchar(10) NOT NULL,
  `translated_text` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_id` (`article_id`,`language`),
  CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (10,2,'ar','{\"title\":\"كيف أن الترجمة بواسطة AI هي تحويل الأخبار المحلية\",\"content\":\"وعلى مدى عقود، واجهت الصحف المحلية مشكلة بسيطة واحدة هي اللغة. وحتى عندما تكون المدينة متعددة اللغات، فإن معظم المنافذ المحلية لا تنشر إلا بلغة واحدة، مما يترك أجزاء كبيرة من السكان دون الحصول بسهولة على معلومات هامة عن السياسة والمدارس والنقل والسلامة العامة. وقد بدأت التطورات الأخيرة في ترجمة AI تغيير ذلك.\\n\\nوالنماذج الحديثة للترجمة العصبية أكثر دقة بكثير من النظم القائمة على القواعد المستخدمة في أوائل العقد الأول من الألفية. وبدلاً من ترجمة الكلمات بالكلمات، فإنها تتعلم أنماطاً من الملايين من الأحكام، مما يساعدها على فهم السياقات والأغبياء والنبرة بشكل أفضل. وهذا يعني، بالنسبة لقاعات الأنباء المحلية، أنها يمكن أن تترجم مقالات طويلة في ثواني ثم أن يكون لها محرر يستعرض بسرعة الناتج، بدلا من دفع تكاليف ترجمة بشرية كاملة من الصفر.\\n\\nغير أن ترجمة AI ليست حلاً سحرياً. ولا يزال يتعين على المحررين التحقق من الأسماء والأرقام والعبارات الحساسة ثقافيا. فبعض أنواع المحتوى، مثل قطع الرأي أو الصبر، كثيرا ما تتطلب إعادة كتابة أكثر حذرا لتجنب سوء الفهم. وهناك العديد من غرف الأنباء التي تعتمد تدفقا للعمل الهجين: وتصدر منظمة العفو الدولية مشروعاً أولياً، ويقوم الصحفيون الثنائيو اللغة أو المحررون بصقل النص قبل نشره.\\n\\nوعلى الرغم من هذه القيود، فإن الأثر على الوصول مرئي بالفعل. In several pilot projects, local outlets that introduced translated versions of their most important stories reported a significant increase in readership from immigrant communities who were previously underserved. وتختبر بعض المدن أيضا الترجمة الفورية للإنذارات الطارئة، وتأكد من أن المعلومات الحاسمة عن المخاطر الجوية أو الصحية الشديدة متاحة بلغات متعددة في غضون دقائق.\\n\\nويتمثل التحدي التالي في بناء نماذج تجارية مستدامة حول هذه الأدوات. والترجمة تخلق قيمة أكبر للقراء، ولكنها تزيد أيضا من عبء العمل التحريري وتكاليف الهياكل الأساسية. وتجري مناقشة كل من المنح والتمويل العام وخدمات الترجمة التحريرية القائمة على الاشتراكات باعتبارها حلولا ممكنة. وما هو واضح هو أن الترجمة الكهربائية لم تعد تجربة غير مجدية، فقد أصبحت بسرعة جزءا أساسيا من الكيفية التي يمكن بها لغرفة الأنباء المحلية أن تخدم مجتمعها المحلي بأسره.\"}','2025-12-03 16:33:20'),(11,2,'ja','{\"title\":\"地域ニュースを変革するAI-Powered Translationの仕組み\",\"content\":\"何十年もの間、地元の新聞は一つの簡単な問題に苦労しています。 都市が多言語化されている場合でも、ほとんどのローカルアウトレットは単一の言語でのみ公開され、政治、学校、交通機関、公共安全に関する重要な情報にアクセスしやすくすることなく、人口の大きな部分を残します。 最近のAI翻訳の進展が始まります。\\n\\n現代のニューラル翻訳モデルは、2000年代初頭のルールベースのシステムよりもはるかに正確です。 単語によって単語を翻訳するのではなく、何百万人もの文からパターンを学びます。これにより、より良いコンテキスト、イディオム、トーンをキャプチャできます。 ローカルのニュースルームでは、長い記事を秒単位で翻訳し、エディタはすぐに出力を見直し、スクラッチから完全な人間の翻訳を支払う代わりに。\\n\\nしかし、AI翻訳は魔法のソリューションではありません。 エディタは、名前、数字、文化的に敏感なフレーズをチェックする必要があります。 意見ピースやサチレなどの特定の種類のコンテンツは、誤解を避けるために、より慎重に書き直す必要があります。 多くのニュースルームは、ハイブリッドワークフローを採用しています。 AIは、出版前の文章を1つ作成し、バイリンガルジャーナリストやフリーランサーがテキストを精製します。\\n\\nこれらの制限にもかかわらず、リーチへの影響は既に表示されます。 いくつかのパイロットプロジェクトでは、翻訳されたバージョンの最も重要な物語を導入したローカルアウトレットは、以前に保存された移民コミュニティから読者層の大きな増加を報告しました。 一部の都市では、緊急アラートのリアルタイム翻訳も実験しています。極端な気象や健康リスクに関する重要な情報は、数分で複数の言語で利用できます。\\n\\n次の課題は、これらのツールの周りに持続可能なビジネスモデルを構築します。 トランスレーションは、読者にとってより価値を創出しますが、編集作業負荷とインフラコストも増加します。 助成金、公的資金およびサブスクリプションベースの翻訳サービスはすべて、可能なソリューションとして議論されています。 クリアとは、AIの翻訳はもはや未来の実験ではなく、地域のニュースルームがコミュニティ全体にどのように役立つかの根本的な部分になるということです.\"}','2025-12-03 16:33:42'),(12,3,'fr','{\"title\":\"Derrière le Paywall : Pourquoi les nouvelles locales ont besoin d\'abonnés pour survivre\",\"content\":\"Pendant de nombreuses années, les journaux locaux dépendaient presque entièrement de la publicité. petites annonces, annonces imprimées et insertions payées pour les journalistes qui ont assisté aux réunions du conseil scolaire, suivi les budgets de la ville et couvert les sports d\'école secondaire. Ce modèle s\'est effondré lorsque les fonds publicitaires ont été transférés vers de grandes plateformes numériques, laissant des points de vente locaux avec des salles de presse en baisse et des lacunes croissantes dans la couverture.\\n\\nPaywalls et abonnements numériques sont un moyen pour les salles de presse d\'essayer de reconstruire une entreprise durable. Au lieu de traiter le site comme un cadeau gratuit, ils présentent le journalisme comme un service qui vaut la peine de payer tout comme la vidéo en streaming ou la musique. Les lecteurs qui s\'abonnent ne se contentent pas d\'acheter l\'accès à des histoires; ils financent efficacement les rapports eux-mêmes.\\n\\nBien sûr, convaincre les gens de payer n\'est pas facile. Beaucoup de lecteurs se sont habitués à du contenu gratuit et paient déjà plusieurs abonnements de divertissement. Les points de vente locaux qui réussissent répondent aux offres d\'adhésion qui comprennent des bulletins d\'information, des événements en direct et des façons directes de parler avec les journalistes. Plus une communauté voit son point de presse comme une ressource civique commune plutôt qu\'un simple site Web, plus les gens sont disposés à le soutenir.\\n\\nL\'avenir à long terme des nouvelles locales sera probablement un mélange d\'abonnements, de philanthropie et de publicité limitée. Mais les paywalls jouent un rôle central dans cette transition, poussant les salles de presse à se concentrer sur la profondeur, la qualité et la confiance au lieu de chasser les pages vues.\"}','2025-12-03 18:50:44'),(13,2,'zh','{\"title\":\"AI-Powered 翻译如何转换本地新闻\",\"content\":\"数十年来,当地报纸一直奋力解决一个简单的问题:语言. 即使一个城市是多语种的,大多数地方的出入口也只用一门语言出版,使得大部分人口无法轻易地获得有关政治,学校,交通和公共安全的重要信息. 最近人工智能翻译的进展正在改变这一点。\\n\\n现代神经翻译模型远比2000年代早期使用的有章可循系统更准确. 他们不是逐字翻译,而是从上百万个句子中学习规律,这帮助他们更好地抓住上下文,平庸和语气. 对于当地的新闻室来说,这意味着他们可以在几秒钟内翻译出长篇的文章,然后让编辑迅速审查产出,而不是从零开始支付完整的人文翻译费.\\n\\n然而,AI翻译并不是一个神奇的解决方案. 编辑器仍需要检查姓名,数字和文化上敏感的短语. 某些类型的内容,如意见片或讽刺,往往需要更仔细地改写来避免误解. 许多新闻室采用混合工作流程: 大赦国际编写了初稿,双语记者或自由职业者在出版前对案文进行了完善。\\n\\n尽管存在这些局限性,但对接触的影响已经很明显。 在几个试点项目中,推出其最重要故事翻译版本的地方机构报告说,以前服务不足的移民社区的读者人数大幅增加。 一些城市还尝试实时翻译紧急警报,确保几分钟内以多种语言提供极端天气或健康风险的关键信息.\\n\\n下一个挑战是围绕这些工具建立可持续的商业模式。 翻译为读者创造了更多的价值,但也增加了编辑工作量和基础设施成本. 赠款、公共资金和基于订阅的翻译服务都正在作为可能的解决办法进行讨论。 显然,AIPower翻译已不再是一种未来实验,它正在迅速成为当地新闻室如何为整个社区服务的核心部分.\"}','2025-12-03 19:07:57'),(14,2,'fr','{\"title\":\"Comment la traduction assistée par l\'IA transforme les nouvelles locales\",\"content\":\"Pendant des décennies, les journaux locaux se sont heurtés à un problème simple : la langue. Même lorsqu\'une ville est multilingue, la plupart des points de vente locaux ne publient que dans une seule langue, laissant une grande partie de la population sans accès facile à des informations importantes sur la politique, les écoles, les transports et la sécurité publique. Les progrès récents dans la traduction de l\'intelligence artificielle commencent à changer cela.\\n\\nLes modèles modernes de traduction neurale sont beaucoup plus précis que les systèmes fondés sur des règles utilisés au début des années 2000. Au lieu de traduire mot par mot, ils apprennent des modèles de millions de phrases, ce qui les aide à mieux saisir le contexte, les idiomes et le ton. Pour les salles de presse locales, cela signifie qu\'elles peuvent traduire de longs articles en quelques secondes et ensuite avoir un éditeur rapidement passer en revue la sortie, au lieu de payer pour une traduction humaine complète à partir de zéro.\\n\\nCependant, la traduction AI n\'est pas une solution magique. Les éditeurs doivent toujours vérifier les noms, les numéros et les phrases culturellement sensibles. Certains types de contenu, comme les pièces d\'opinion ou la satire, nécessitent souvent une réécriture plus soigneuse pour éviter les malentendus. De nombreuses salles de presse adoptent un flux de travail hybride : AI produit un premier projet, et des journalistes bilingues ou des freelances peaufinent le texte avant publication.\\n\\nMalgré ces limites, l\'impact sur la portée est déjà visible. Dans plusieurs projets pilotes, les points de vente locaux qui ont présenté des versions traduites de leurs histoires les plus importantes ont signalé une augmentation importante du nombre de lecteurs provenant de communautés d\'immigrants qui étaient auparavant mal desservies. Certaines villes expérimentent également la traduction en temps réel des alertes d\'urgence, en veillant à ce que des informations critiques sur les phénomènes météorologiques extrêmes ou les risques pour la santé soient disponibles en plusieurs langues en quelques minutes.\\n\\nLe prochain défi consistera à élaborer des modèles d\'affaires durables autour de ces outils. La traduction crée plus de valeur pour les lecteurs, mais elle augmente également la charge de travail éditoriale et les coûts d\'infrastructure. Les subventions, le financement public et les services de traduction par abonnement sont tous des solutions possibles. Ce qui est clair, c\'est que la traduction assistée par l\'IA n\'est plus une expérience futuriste, elle devient rapidement un élément central de la façon dont les salles de nouvelles locales peuvent servir toute leur communauté.\"}','2025-12-03 19:08:17'),(15,2,'it','{\"title\":\"Come AI-Powered Traduzione sta trasformando le notizie locali\",\"content\":\"Per decenni, i giornali locali hanno lottato con un semplice problema: la lingua. Anche quando una città è poliglotta, la maggior parte dei punti vendita locali pubblica solo in una singola lingua, lasciando grandi parti della popolazione senza facile accesso a informazioni importanti su politica, scuole, trasporti e sicurezza pubblica. Recenti progressi nella traduzione AI stanno iniziando a cambiare questo.\\n\\nI moderni modelli di traduzione neurale sono molto più precisi dei sistemi basati sulle regole utilizzati nei primi anni 2000. Invece di tradurre parola per parola, imparano modelli da milioni di frasi, che li aiuta a meglio catturare contesto, idiomi e tono. Per le newsroom locali, questo significa che possono tradurre articoli lunghi in pochi secondi e poi avere un editor di rivedere rapidamente l\'output, invece di pagare per una traduzione umana completa da zero.\\n\\nTuttavia, la traduzione AI non è una soluzione magica. Gli editor devono ancora controllare nomi, numeri e frasi culturalmente sensibili. Alcuni tipi di contenuti, come i pezzi di opinione o la satira, richiedono spesso una riscrittura più attenta per evitare malintesi. Molte newsroom stanno adottando un flusso di lavoro ibrido: AI produce una prima bozza e giornalisti bilingue o freelance perfezionano il testo prima della pubblicazione.\\n\\nNonostante queste limitazioni, l\'impatto sulla portata è già visibile. In diversi progetti pilota, i punti vendita locali che hanno introdotto versioni tradotte delle loro storie più importanti hanno riferito un significativo aumento del lettore da parte delle comunità immigrate che erano precedentemente sottoserve. Alcune città stanno anche sperimentando la traduzione in tempo reale di avvisi di emergenza, assicurandosi che le informazioni critiche sui rischi meteorologici estremi o sanitari siano disponibili in più lingue in pochi minuti.\\n\\nLa prossima sfida sarà la costruzione di modelli di business sostenibili intorno a questi strumenti. La traduzione crea più valore per i lettori, ma aumenta anche il carico di lavoro editoriale e i costi delle infrastrutture. Le sovvenzioni, i finanziamenti pubblici e i servizi di traduzione abbonati sono tutti discussi come possibili soluzioni. Ciò che è chiaro è che la traduzione AIpowered non è più un esperimento futuristico che sta rapidamente diventando una parte fondamentale di come le newsroom locali possono servire la loro intera comunità.\"}','2025-12-03 19:08:32'),(16,5,'ar','{\"title\":\"النشرات الإخبارية بوصفها الجبهة الجديدة\",\"content\":\"افتحي بريدك الإلكتروني في الصباح وستجدين على الأقل رسالة إخبارية مشفّرة تلخص قصص اليوم ما بدأ كقناة توزيع بسيطة أصبح أحد أهم الأدوات لغرفة الأخبار وتنشئ النشرات الإخبارية علاقة مباشرة مع القراء الذين لا يغلب عليهم الخوارزميات أو وسائل الإعلام الاجتماعية.\\n\\nبالنسبة للمنافذ المحلية، هذه العلاقة قيمة بشكل خاص. ويمكن أن تبرز جلسة إحاطة صباحية مصممة تصميما جيدا قرارات مجالس المدن، وتنبيهات الطقس، وتحديث المدارس، والأحداث المجتمعية في عدد قليل فقط من الفقرات. ومن الأرجح أن ينتقل القراء الذين يعتمدون على الرسالة الإخبارية الموجهة إلى المقالات الكاملة، وأن يصبحوا في نهاية المطاف مشتركين.\\n\\nرسائل إخبارية ناجحة توازن بين الفائدة والشخصية وهي توفر وصلات وملخصات واضحة، ولكن لها أيضا صوت مشهود به. ويشتمل العديد من المحررين على مذكرات قصيرة تشرح لماذا تهم القصة أو كيفية الإبلاغ عنها. وتساعد هذه الشفافية على بناء الثقة في وقت يسهل فيه نشر المعلومات الخاطئة.\\n\\nومع تزايد ازدحام الصناديق، سيزداد الضغط على إيصال القيمة في كل طبعة. غرف الأخبار التي تعامل رسائلها الإخبارية على أنها منتجة أساسية أكثر من النظرة بعد ذلك.\"}','2025-12-03 20:22:18'),(17,2,'de','{\"title\":\"Wie KI-Powered-Übersetzung lokale Nachrichten transformiert\",\"content\":\"Seit Jahrzehnten haben lokale Zeitungen mit einem einfachen Problem gekämpft: Sprache. Auch wenn eine Stadt mehrsprachig ist, veröffentlichen die meisten lokalen Steckdosen nur in einer einzigen Sprache, so dass große Teile der Bevölkerung ohne einfachen Zugang zu wichtigen Informationen über Politik, Schulen, Transport und öffentliche Sicherheit. Die jüngsten Fortschritte in der KI-Übersetzung beginnen, das zu ändern.\\n\\nModerne neurale Translationsmodelle sind viel genauer als die in den frühen 2000er Jahren verwendeten regelbasierten Systeme. Anstatt Wort nach Wort zu übersetzen, lernen sie Muster aus Millionen von Sätzen, die ihnen helfen, Kontext, Idiome und Ton besser zu erfassen. Für lokale Newsrooms bedeutet das, dass sie lange Artikel in Sekunden übersetzen können und dann einen Editor schnell die Ausgabe überprüfen, anstatt für eine vollständige menschliche Übersetzung von Grund auf zu zahlen.\\n\\nKI-Übersetzung ist jedoch keine magische Lösung. Editoren müssen immer noch Namen, Zahlen und kulturell sensible Phrasen überprüfen. Bestimmte Arten von Inhalten, wie Meinungsstücke oder Satire, erfordern oft eine sorgfältigere Neuschrift, um Missverständnisse zu vermeiden. Viele Newsrooms übernehmen einen hybriden Workflow: AI produziert einen ersten Entwurf, und zweisprachige Journalisten oder Freiberufler verfeinern den Text vor der Veröffentlichung.\\n\\nTrotz dieser Einschränkungen ist der Einfluss auf die Reichweite bereits sichtbar. In mehreren Pilotprojekten berichteten lokale Verkaufsstellen, die übersetzte Versionen ihrer wichtigsten Geschichten eingeführt hatten, eine signifikante Zunahme der Leserschaft von Immigrantengemeinschaften, die zuvor unterbewertet wurden. Einige Städte experimentieren auch mit Echtzeit-Übersetzung von Notfallalarmen, um sicherzustellen, dass kritische Informationen über extreme Wetter- oder Gesundheitsrisiken innerhalb von Minuten in mehreren Sprachen verfügbar sind.\\n\\nDie nächste Herausforderung wird es sein, nachhaltige Geschäftsmodelle um diese Werkzeuge zu bauen. Übersetzung schafft mehr Wert für Leser, aber es erhöht auch redaktionelle Arbeitsbelastung und Infrastrukturkosten. Zuwendungen, öffentliche Förder- und Subskriptionsdienste werden als mögliche Lösungen diskutiert. Es ist klar, dass AIpowered Translation nicht mehr ein futuristisches Experiment ist, es wird schnell ein Kernteil davon, wie lokale Newsrooms ihre ganze Gemeinschaft bedienen können.\"}','2025-12-03 20:30:26'),(18,2,'ko','{\"title\":\"AI-Powered Translation는 현지 뉴스를 전달하는 방법\",\"content\":\"수십 년 동안 현지 신문은 하나의 간단한 문제로 투쟁했습니다. 언어. 도시가 다언어일 때, 대부분의 로컬 아울렛은 단일 언어로 출판되며, 정치, 학교, 교통 및 공공 안전에 대한 중요한 정보에 접근하지 않고 인구의 큰 부분을 떠나고 있습니다. AI 번역의 최근 발전은 그 변경을 시작합니다.\\n\\n현대 신경 번역 모델은 초기 2000 년대에 사용되는 룰 기반 시스템보다 훨씬 정확합니다. 단어로 단어를 번역하는 대신, 그들은 더 나은 캡처 컨텍스트, idioms 및 톤을 돕는 수백만 문장에서 패턴을 배울. 로컬 뉴스룸의 경우, 이것은 그들이 몇 초안에 긴 기사를 번역할 수 있고 그 후에 편집자는 빨리 찰상에서 가득 차있는 인간적인 번역을 위해 지불하는 대신 산출을 검토합니다.\\n\\n그러나 AI 번역은 마술 솔루션이 아닙니다. 편집기는 여전히 이름, 숫자 및 문화적으로 민감한 구문을 확인해야합니다. 특정 유형의 내용, 같은 의견 조각 또는 satire, 종종 더 많은 주의적인 rewriting to avoid misunderstanding. 많은 뉴스룸은 하이브리드 워크플로우를 채택하고 있습니다. AI는 첫 번째 초안과 이중 언어 기자 또는 프리랜서가 출판하기 전에 텍스트를 정제합니다.\\n\\n이러한 제한에도 불구하고, 도달에 미치는 영향은 이미 볼 수 있습니다. 몇 가지 파일럿 프로젝트에서, 그들의 가장 중요한 이야기의 번역 된 버전을 도입 한 지역 출구는 이전에 보존 된 이민 커뮤니티의 독자적인 증가를보고. 일부 도시는 긴급 경고의 실시간 번역으로 실험하고, 극단적 인 날씨 또는 건강 위험에 대한 중요한 정보가 몇 분 안에 여러 언어로 제공됩니다.\\n\\n다음 도전은 이러한 도구의 지속 가능한 비즈니스 모델을 구축 할 것입니다. 번역은 독자들에게 더 많은 가치를 창출하지만, 에디터의 워크로드와 인프라 비용을 증가시킵니다. Grants, public funding and subscriptionbased translation services are all being discussed as possible solution. 분명히 AIpowered 번역은 더 이상 미래 실험이 아니라 로컬 뉴스룸이 전체 커뮤니티를 봉사 할 수있는 핵심 부분이 될 것입니다.\"}','2025-12-03 20:30:50'),(19,3,'ar','{\"title\":\"وراء الجدار: لماذا الأخبار المحلية تحتاج للمجندين إلى البقاء\",\"content\":\"ولسنوات عديدة، تعتمد الصحف المحلية كليا تقريبا على الإعلان. وتُقيَّد المصنفات والإعلانات المطبوعة والإضافات المدفوعة للمراسلين الذين حضروا اجتماعات مجالس المدارس، ويتبعون ميزانيات المدن ويغطيون رياضات المدارس الثانوية. وقد انهار هذا النموذج عندما تحولت الدولار إلى منابر رقمية كبيرة، مما ترك منافذ محلية مع انخفاض عدد غرف الأنباء وزيادة فجوات التغطية.\\n\\nوخطوط الأجور والاشتراكات الرقمية هي إحدى الطرق التي تحاول بها غرف الأنباء إعادة بناء عمل مستدام. وبدلاً من أن يعاملوا الموقع على شبكة الإنترنت كمنحة مجانية، يقدمون الصحافة كخدمة تستحق الدفع من أجلها مثل بث الفيديو أو الموسيقى. والقراء الذين يشاركون لا يشترون فقط الوصول إلى القصص؛ وهم يمولون بشكل فعال التقارير نفسها.\\n\\nبالطبع، إقناع الناس بالدفع ليس سهلاً وقد استُخدم كثير من القراء للمحتوى الحر وهم يدفعون بالفعل مقابل عدة اشتراكات في الترفيه. وتستجيب المنافذ المحلية الناجحة للعروض التي تشمل الرسائل الإخبارية والأحداث الحية وسبل التحدث مع الصحفيين. وكلما رأى المجتمع المحلي منفذه الإخبارية كمورد مدني مشترك بدلا من مجرد موقع على شبكة الإنترنت، فإن الناس الأكثر استعدادا لدعمه.\\n\\nومن المرجح أن يكون المستقبل الطويل الأجل للأنباء المحلية مزيجا من الاشتراكات، والفلسفة، والإعلانات المحدودة. غير أن جدران الدفع تؤدي دوراً محورياً في هذا الانتقال، مما يدفع غرف الأنباء إلى التركيز على العمق والجودة والثقة بدلاً من مطاردة آراء الصفحات.\"}','2025-12-11 08:54:23'),(20,3,'zh','{\"title\":\"在活墙后面: 为什么本地新闻需要订阅者来生存\",\"content\":\"多年来,当地报纸几乎完全依赖广告。 参加学校董事会会议的记者的分类、印刷广告和插件,遵循城市预算并覆盖高中体育。 当广告美元转向大型数字平台时,这一模式崩溃了,使当地机构的新闻室不断缩小,报道差距越来越大。\\n\\n付费墙和数字订阅是新闻室试图重建可持续业务的方式之一。 他们不把网站视为免费赠送,而是将新闻业视为一种值得像流媒体或音乐一样花钱的服务。 订阅的读者不仅仅是购买获取故事的机会,而是为报道本身提供有效的资金。\\n\\n当然,说服人们付出代价并不容易。 许多读者已经习惯于免费内容,并且已经支付一些娱乐订阅费. 成功的当地机构正在响应会员式的提议,其中包括通讯、现场活动和直接与记者交谈的方式。 社会越将新闻发布视为共同的公民资源,\\n\\n当地新闻的长期未来很可能是订阅、慈善和有限广告的混合。 但付费墙在这场过渡中发挥着中心作用,推动新闻室注重深度,质量和信任,而不是追逐页面浏览.\"}','2025-12-11 08:54:49'),(21,3,'es','{\"title\":\"Detrás del Paywall: ¿Por qué las noticias locales necesitan suscriptores para sobrevivir\",\"content\":\"Durante muchos años, los periódicos locales dependían casi totalmente de la publicidad. Clasificados, anuncios impresos e insertos pagados por los reporteros que asistieron a las reuniones de la junta escolar, siguieron los presupuestos de la ciudad y cubrieron los deportes de secundaria. Ese modelo se derrumbó cuando los dólares ad se desplazaron a grandes plataformas digitales, dejando los puntos de venta locales con la reducción de las salas de noticias y las crecientes brechas de cobertura.\\n\\nPaywalls y suscripciones digitales son de una manera que las salas de prensa están tratando de reconstruir un negocio sostenible. En lugar de tratar el sitio web como un regalo gratuito, presentan el periodismo como un servicio que vale la pena pagar por igual como streaming de vídeo o música. Los lectores que se suscriben no están simplemente comprando acceso a las historias; están financiando efectivamente el propio informe.\\n\\nPor supuesto, convencer a la gente para que pague no es fácil. Muchos lectores se han utilizado para el contenido libre y ya están pagando por varias suscripciones de entretenimiento. Los exitosos medios locales están respondiendo con ofertas de estilo de membresía que incluyen boletines informativos, eventos en vivo y formas directas de hablar con periodistas. Mientras más una comunidad ve su salida de noticias como un recurso cívico compartido en lugar de sólo un sitio web, las personas más dispuestas son apoyarlo.\\n\\nEl futuro a largo plazo de las noticias locales probablemente será una mezcla de suscripciones, filantropía y publicidad limitada. Pero las paredes de pago están jugando un papel central en esta transición, empujando a las salas de noticias a centrarse en la profundidad, la calidad y la confianza en lugar de perseguir las vistas de la página.\"}','2025-12-14 08:45:51'),(22,4,'es','{\"title\":\"De Print a Podcast: Cómo los Newsrooms están reemplazando sus mejores historias\",\"content\":\"El diario llegaba sólo en una forma: tinta en papel. Hoy en día, muchas de las mismas salas de prensa están experimentando con series de audio, minidocumentarios y clips sociales que remezclan su reportaje para nuevos públicos. El objetivo es gente sencilla donde están, ya sea en un viaje, en el gimnasio o desplazarse por teléfono.\\n\\nConvertir un largo artículo de investigación en un podcast, por ejemplo, permite a los reporteros traer clips de entrevista, sonido ambiente y narración que pueden hacer que temas complejos se sientan más personales. Algunos medios incluso están construyendo pequeños estudios de audio dentro de sus oficinas para que los periodistas puedan registrar actualizaciones a medida que se desarrollan historias.\\n\\nEste cambio no significa que los artículos escritos están desapareciendo. En su lugar, forman la columna vertebral de una estrategia multiformato. Un solo proyecto de presentación de informes podría generar un artículo de características, un vídeo explicativo, un episodio de podcast y un breve post social que resume el hallazgo clave. Cuando todos estos formatos se unen entre sí, los lectores pueden elegir lo profundo que quieren ir.\\n\\nPara los noticieros locales, el mayor desafío es la capacidad. Producir audio y vídeo de calidad requiere nuevas habilidades y equipos. Muchos se asocian con universidades o productores independientes para compartir conocimientos especializados manteniendo el control editorial interna.\"}','2025-12-14 08:45:53'),(23,5,'es','{\"title\":\"Newsletters as the New Front Page\",\"content\":\"Abra su correo electrónico por la mañana y es probable que encuentre al menos un boletín curado resumiendo las mejores historias del día. Lo que comenzó como un simple canal de distribución se ha convertido en una de las herramientas más importantes para las salas de prensa. Los boletines crean una relación directa con los lectores que no está filtrada por algoritmos o redes sociales.\\n\\nPara los outlets locales, esta relación es especialmente valiosa. Una sesión informativa bien diseñada de la mañana puede poner de relieve las decisiones del ayuntamiento, las alertas meteorológicas, las actualizaciones escolares y los eventos comunitarios en unos pocos párrafos. Los lectores que confían en el boletín para orientación son más propensos a hacer clic a través de los artículos completos y, en última instancia, a convertirse en suscriptores.\\n\\nLos boletines exitosos equilibran la utilidad y la personalidad. Proporcionan enlaces y resúmenes claros, pero también tienen una voz reconocible. Muchos editores incluyen notas breves que explican por qué una historia importa o cómo se informó. Esa transparencia ayuda a crear confianza en un momento en que la desinformación es fácil de difundir.\\n\\nA medida que las cajas se llenan más, la presión para ofrecer valor en cada edición sólo crecerá. Newsrooms que tratan sus boletines como un producto básico más que un afterthoughtare ya viendo mayor compromiso y mayor lealtad de los lectores.\"}','2025-12-14 08:45:54'),(24,2,'es','{\"title\":\"Cómo la traducción impulsada por AI está transformando las noticias locales\",\"content\":\"Durante décadas, los periódicos locales han luchado con un simple problema: el lenguaje. Incluso cuando una ciudad es multilingüe, la mayoría de los locales sólo publican en un solo idioma, dejando grandes partes de la población sin fácil acceso a información importante sobre política, escuelas, transporte y seguridad pública. Los avances recientes en la traducción de AI están empezando a cambiar eso.\\n\\nLos modelos modernos de traducción neuronal son mucho más precisos que los sistemas basados en normas utilizados a principios de los años 2000. En lugar de traducir palabra por palabra, aprenden patrones de millones de frases, lo que les ayuda a captar mejor el contexto, las expresiones y el tono. Para las salas de prensa locales, esto significa que pueden traducir artículos largos en segundos y luego tener un editor revisar rápidamente la salida, en lugar de pagar una traducción humana completa desde cero.\\n\\nSin embargo, la traducción de AI no es una solución mágica. Los editores todavía necesitan comprobar nombres, números y frases culturalmente sensibles. Ciertos tipos de contenido, como piezas de opinión o sátira, a menudo requieren una reescritura más cuidadosa para evitar malentendidos. Muchas salas de prensa están adoptando un flujo de trabajo híbrido: AI produce un primer borrador, y periodistas bilingües o freelancers refinan el texto antes de la publicación.\\n\\nA pesar de estas limitaciones, el impacto en el alcance ya es visible. En varios proyectos piloto, los medios locales que presentaron versiones traducidas de sus historias más importantes reportaron un aumento significativo en el número de lectores de comunidades inmigrantes que anteriormente eran insuficientes. Algunas ciudades también están experimentando con la traducción en tiempo real de alertas de emergencia, asegurándose de que la información crítica sobre el clima extremo o los riesgos de salud está disponible en varios idiomas en cuestión de minutos.\\n\\nEl próximo desafío será construir modelos de negocio sostenibles en torno a estas herramientas. La traducción crea más valor para los lectores, pero también aumenta el volumen de trabajo editorial y los costos de infraestructura. Las subvenciones, la financiación pública y los servicios de traducción basados en la suscripción se examinan como posibles soluciones. Lo que está claro es que la traducción impulsada por AI ya no es un experimento futurista que se está convirtiendo rápidamente en una parte fundamental de cómo las salas de noticias locales pueden servir a toda su comunidad.\"}','2025-12-14 08:45:55'),(25,4,'de','{\"title\":\"Von Print bis Podcast: Wie Newsrooms ihre besten Geschichten umsetzen\",\"content\":\"Die Tageszeitung kam nur in einer Form an: Tinte auf Papier. Heute experimentieren viele der gleichen Newsrooms mit Audio-Serien, Minidokumentaren und sozialen Clips, die ihre Berichterstattung für neue Publikum wieder mischen. Das Ziel ist einfache Leute, wo sie sind, ob auf einer Straße, im Fitnessstudio oder Scrolling auf einem Telefon.\\n\\nEin langer Untersuchungsartikel in eine Podcast zu verwandeln, ermöglicht Reportern, Interviewclips, Umgebungsgeräusche und Erzählungen einzubringen, die komplexe Themen persönlicher fühlen können. Einige Outlets bauen sogar kleine Audiostudios in ihren Büros, so dass Journalisten Updates aufnehmen können, wenn Geschichten entstehen.\\n\\nDiese Verschiebung bedeutet nicht, dass geschriebene Artikel verschwinden. Stattdessen bilden sie das Rückgrat einer Multiformatstrategie. Ein einziges Reporting-Projekt kann einen Feature-Artikel, ein Erklärer-Video, eine Podcast-Folge und einen kurzen Social-Post zur Zusammenfassung der Schlüsselfindung generieren. Wenn alle diese Formate miteinander verlinken, können die Leser wählen, wie tief sie gehen wollen.\\n\\nFür lokale Newsrooms ist die größte Herausforderung die Kapazität. Qualität Audio und Video zu produzieren erfordert neue Fähigkeiten und Ausrüstung. Viele sind mit Universitäten oder unabhängigen Produzenten zusammenarbeiten, um Know-how zu teilen, während die redaktionelle Kontrolle inhouse bleibt.\"}','2025-12-14 08:46:46'),(26,4,'ar','{\"title\":\"من المطبوع إلى Podcast: How Newsrooms are Repacking their Best Stories\",\"content\":\"The daily newspaper used to arrive only in one form: ink on paper. واليوم، يختبر العديد من نفس غرف الأنباء سلسلة سمعية، ووثائق صغيرة، ومقاطع اجتماعية تعيد صياغة تقاريرها لجمهور جديد. الهدف بسيط للناس حيث هم، سواء كان ذلك على مظلة، في صالة الألعاب الرياضية أو على الهاتف.\\n\\nوعلى سبيل المثال، فإن تحويل مقالة تحقيق طويلة إلى دوامة، يتيح للمراسلين جلب مقاطع المقابلات، والصوت الملموس، والمناورة التي يمكن أن تجعل المواضيع المعقدة أكثر شخصية. Some outlets are even building small audio Stus inside their offices so that journalists can record updates as stories develop.\\n\\nهذا التحول لا يعني أن المقالات المكتوبة تختفي وبدلا من ذلك، فإنها تشكل العمود الفقري لاستراتيجية متعددة الأشكال. A single reporting project might generate a feature article, an explainer video, a podcast episode and a short social post summarizing the key finding. عندما يربط كل هذه الأشكال بعضها ببعض، يمكن للقراء أن يختاروا مدى عمق رغبتهم في الذهاب.\\n\\nفي غرف الأخبار المحلية، أكبر تحدي هو القدرة. ويتطلب إنتاج مواد صوتية وفيديوية عالية الجودة مهارات ومعدات جديدة. ويشترك كثيرون مع الجامعات أو المنتجين المستقلين في تبادل الخبرات مع الحفاظ على الرقابة التحريرية الداخلية.\"}','2025-12-14 08:50:19'),(27,3,'de','{\"title\":\"Hinter der Paywall: Warum lokale Nachrichten Abonnenten zum Überleben benötigen\",\"content\":\"Seit vielen Jahren haben lokale Zeitungen fast ausschließlich auf Werbung gelogen. Kleinanzeigen, Druckanzeigen und Einlagen, die für die Reporter bezahlt wurden, die an Schultafelsitzungen teilgenommen haben, folgten den Stadtbudgets und bedeckten High School-Sport. Dieses Modell brach zusammen, wenn Ad-Dollar auf große digitale Plattformen verschoben, verlassen lokale Steckdosen mit schrumpfenden Newsrooms und wachsende Deckung Lücken.\\n\\nPaywalls und digitale Abonnements sind eine Möglichkeit, Newsrooms versuchen, ein nachhaltiges Geschäft wieder aufzubauen. Anstatt die Website als kostenloses Giveaway zu behandeln, präsentieren sie Journalismus als einen Dienst, der es wert ist, dafür zu zahlen, wie Streaming Video oder Musik. Leser, die sich abonnieren, kaufen nicht einfach Zugang zu Geschichten; sie finanzieren die Berichterstattung selbst effektiv.\\n\\nNatürlich ist überzeugende Menschen zu zahlen nicht einfach. Viele Leser haben sich zum kostenlosen Inhalt gewöhnt und zahlen bereits für mehrere Unterhaltungs-Abonnements. Erfolgreiche lokale Outlets reagieren mit Mitgliedschaft-Stil-Angebote, die Newsletter, Live-Ereignisse und direkte Möglichkeiten, mit Reportern zu sprechen. Je mehr eine Gemeinschaft ihre Nachrichten-Ausgang als eine gemeinsame Bürger Ressource sieht, anstatt nur eine Website, desto bereiter Menschen sind es, sie zu unterstützen.\\n\\nDie langfristige Zukunft der lokalen Nachrichten wird wahrscheinlich eine Mischung aus Abonnements, Philanthropie und begrenzte Werbung sein. Aber Paywalls spielen eine zentrale Rolle in diesem Übergang, Push-Newsrooms, um sich auf Tiefe, Qualität und Vertrauen zu konzentrieren, anstatt Seite Ansichten zu verfolgen.\"}','2025-12-14 09:29:47'),(28,5,'de','{\"title\":\"Newsletter als Neue Frontseite\",\"content\":\"Öffnen Sie Ihre E-Mail am Morgen und Sie werden wahrscheinlich mindestens einen kuratierten Newsletter finden, der die Top-Geschichten des Tages zusammenfasst. Was als einfacher Vertriebskanal begann, ist zu einem der wichtigsten Tools für Newsrooms geworden. Newsletter erstellen eine direkte Beziehung zu Lesern, die nicht durch Algorithmen oder Social Media Feeds gefiltert werden.\\n\\nFür lokale Steckdosen ist diese Beziehung besonders wertvoll. Ein gut gestalteter Morgenbriefing kann Stadtratsentscheidungen, Wetterwarnungen, Schulaktualisierungen und Gemeinschaftsveranstaltungen in nur wenigen Punkten hervorheben. Leser, die sich auf den Newsletter für die Orientierung verlassen, sind wahrscheinlicher, durch zu vollständigen Artikeln zu klicken und schließlich, um Abonnenten zu werden.\\n\\nErfolgreiche Newsletter Balance Nutzen und Persönlichkeit. Sie bieten klare Links und Zusammenfassungen, aber sie haben auch eine erkennbare Stimme. Viele Editoren enthalten kurze Notizen, die erklären, warum eine Geschichte zählt oder wie sie berichtet wurde. Diese Transparenz hilft, Vertrauen zu einer Zeit aufzubauen, in der Fehlinformationen leicht zu verbreiten sind.\\n\\nDa die Postfächer mehr überfüllt werden, wird der Druck, Wert in jeder Ausgabe zu liefern, nur wachsen. Newsrooms, die ihre Newsletter als Kernprodukt behandeln, als ein Afterthoughtare bereits mehr Engagement und eine stärkere Leserbindung sehen.\"}','2025-12-14 09:29:53'),(29,7,'de','{\"title\":\"Die stille Revolution: Wie Edge Computing das Internet umgestaltet\",\"content\":\"Seit Jahren ist Cloud Computing das Rückgrat moderner digitaler Dienste. Von Streaming-Plattformen bis zur Enterprise-Software haben zentrale Rechenzentren alles hinter den Kulissen betrieben. Aber da Anwendungen schnellere Reaktionen und Echtzeit-Intelligenz erfordern, wird ein neues Paradigma leise übernommen: Edge Computing.\\n\\nWas ist Edge Computing?\\n\\nEdge Computing verschiebt die Datenverarbeitung näher, wo sie erzeugt wird – auf Geräten wie Smartphones, Sensoren, Fahrzeuge und lokalen Servern – anstatt alles in entfernte Cloud-Datenzentren zu senden. Diese Nähe reduziert die Latenz drastisch, verbessert die Zuverlässigkeit und senkt die Bandbreitenkosten.\\n\\nAnstatt „Kann die Wolke damit umgehen?“ fragen die Ingenieure jetzt: „Können diese Daten sogar das Gerät verlassen? „\\n\\nWarum Edge Computing jetzt\\n\\nMehrere technologische Verschiebungen haben Edge Computing nicht nur nützlich, sondern notwendig gemacht:\\n\\n5G Netzwerke ermöglichen ultra-niedrige Latenzkommunikation\\n\\nIoT Explosion hat Milliarden von angeschlossenen Geräten eingeführt\\n\\nKI an der Kante ermöglicht on-device Entscheidungsfindung\\n\\nDatenschutzbestimmungen beschränken unnötige Datentransfers\\n\\nFür Anwendungen wie autonomes Fahren, industrielle Automatisierung oder Remote Healthcare sind Millisekunden wichtig – und die Cloud allein ist oft zu langsam.\\n\\nReal-World Anwendungen\\nSmart Cities\\n\\nVerkehrsleuchten, die sich in Echtzeit anpassen, Überwachungssysteme, die Anomalien sofort erkennen, und Energienetze, die sich selbst optimieren alle auf Edge Computing verlassen.\\n\\nGesundheit\\n\\nWearable Geräte können Patientendaten lokal analysieren, Alarme auslösen, ohne auf die Cloud-Verarbeitung zu warten – möglicherweise Leben retten.\\n\\nInhalt Lieferung & Medien\\n\\nEdge-Server reduzieren die Pufferung, verbessern die Streaming-Qualität und personalisieren die Content-Lieferung näher an den Benutzer.\\n\\nEdge vs Cloud: Kein Ersatz\\n\\nTrotz häufiger Missverständnisse ersetzt Edge Computing die Cloud nicht. Stattdessen ergänzt es es.\\n\\nKantengriffe Echtzeitverarbeitung und Filterung\\n\\nCloud verwaltet Langzeitspeicherung, Analytik und Orchestrierung\\n\\nDie Zukunft gehört zu hybriden Architekturen, in denen Intelligenz über Geräte, Randknoten und zentralisierte Wolken verteilt wird.\\n\\nSicherheit am Rand\\n\\nDie Verarbeitung von Daten reduziert lokal die Exposition, führt aber auch neue Sicherheitsprobleme ein. Edge-Geräte sind oft physisch zugänglich und weit verbreitet, so dass sie härter, um gleichmäßig zu sichern. Verschlüsselung, Geräteauthentifizierung und sichere Firmware-Updates sind nun kritische Komponenten der Edge-Infrastruktur.\\n\\nDie Straße\\n\\nDa KI-Modelle kleiner und effizienter werden, erwarten mehr Intelligenz sich von zentralisierten Systemen zu entfernen. Edge Computing spielt eine Schlüsselrolle in:\\n\\nAutonome Systeme\\n\\nPersonalisierte digitale Erfahrungen\\n\\nEchtzeitanalyse\\n\\nNachhaltiges Computing durch Reduzierung der Datenübertragung\\n\\nDas Internet ist nicht mehr nur zentralisiert – es wird überall.\"}','2025-12-17 17:42:22'),(30,4,'nl','{\"title\":\"Van afdrukken tot podcast: hoe Newsrooms hun beste verhalen herverpakking\",\"content\":\"De krant kwam vroeger maar in één vorm aan: inkt op papier. Vandaag de dag experimenteren veel van dezelfde nieuwskamers met audioseries, minidocumentaires en social clips die hun remixen voor nieuwe doelgroepen. Het doel is simpelweg mensen ontmoeten waar ze zijn, of dat nu op een pendelreis is, in de sportschool of scrollen op een telefoon.\\n\\nHet omzetten van een lang onderzoeksartikel in een podcast, bijvoorbeeld, stelt verslaggevers in staat interviewclips, ambient sound en vertelling in te brengen die complexe onderwerpen persoonlijker kunnen maken. Sommige outlets bouwen zelfs kleine audiostudio\'s in hun kantoren zodat journalisten updates kunnen opnemen naarmate verhalen zich ontwikkelen.\\n\\nDeze verschuiving betekent niet dat geschreven artikelen verdwijnen. In plaats daarvan vormen ze de ruggengraat van een multiformatstrategie. Een enkel rapportageproject kan een functieartikel, een uitlegvideo, een podcast episode en een korte sociale post genereren waarin de belangrijkste bevindingen worden samengevat. Wanneer al deze formaten link naar elkaar, lezers kunnen kiezen hoe diep ze willen gaan.\\n\\nVoor lokale nieuwszalen is de grootste uitdaging capaciteit. Het produceren van kwaliteit audio en video vereist nieuwe vaardigheden en apparatuur. Velen werken samen met universiteiten of onafhankelijke producenten om expertise te delen en tegelijkertijd de redactionele controle intern te houden.\"}','2025-12-17 19:40:54');
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_game_progress`
--

DROP TABLE IF EXISTS `user_game_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_game_progress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `game_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` text COLLATE utf8mb4_unicode_ci,
  `score` int DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_game` (`user_id`,`game_id`),
  CONSTRAINT `user_game_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_game_progress`
--

LOCK TABLES `user_game_progress` WRITE;
/*!40000 ALTER TABLE `user_game_progress` DISABLE KEYS */;
INSERT INTO `user_game_progress` VALUES (1,1,'wordle','null',0,'2025-12-14 17:19:49'),(9,1,'spellingbee','{\"foundWords\":[\"LOCAL\",\"GOAL\"]}',6,'2025-12-14 16:36:07'),(22,1,'connections','null',0,'2025-12-14 17:22:02');
/*!40000 ALTER TABLE `user_game_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) DEFAULT '0',
  `status` enum('active','suspended') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Hazem','7azem0.0hassan@gmail.com','$2y$10$FUX3Zn2Fky8rw.hQ/QBAMe16qBgiA/MQenAkI/dReO9DRQERgy56S','2025-11-20 13:46:45',1,'active'),(5,'6azem','7azem00.0hassan@gmail.com','$2y$10$6hDKl.7OMstpEzk0zVLeWeCDTA48oG3BgH.uS1f6x6CJFJX9tHor.','2025-11-20 13:57:20',0,'active'),(6,'5azem','7azem00.00hassan@gmail.com','$2y$10$3u5fQYgeDqJRTCTsexTkSOwgWw8j6pmekwPO/qK7sY2GNpt4k1ika','2025-11-20 13:57:56',0,'active'),(7,'Not Admin','Not@gmail.com','$2y$10$Jh7Hxhb0Le6rvrwlAubVh.lDrqG.zz/ZRimZSjw3nApcKtytUO4Xu','2025-12-17 17:07:43',0,'active');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-17 19:52:18
