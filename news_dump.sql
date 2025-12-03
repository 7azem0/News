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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (2,'How AI-Powered Translation is Transforming Local News','For decades, local newspapers have struggled with one simple problem: language. Even when a city is multilingual, most local outlets only publish in a single language, leaving large parts of the population without easy access to important information about politics, schools, transportation and public safety. Recent advances in AI translation are starting to change that.\n\nModern neural translation models are far more accurate than the rulebased systems used in the early 2000s. Instead of translating word by word, they learn patterns from millions of sentences, which helps them better capture context, idioms and tone. For local newsrooms, this means they can translate long articles in seconds and then have an editor quickly review the output, instead of paying for a full human translation from scratch.\n\nHowever, AI translation is not a magic solution. Editors still need to check names, numbers and culturally sensitive phrases. Certain types of content, such as opinion pieces or satire, often require more careful rewriting to avoid misunderstandings. Many newsrooms are adopting a hybrid workflow: AI produces a first draft, and bilingual journalists or freelancers refine the text before publication.\n\nDespite these limitations, the impact on reach is already visible. In several pilot projects, local outlets that introduced translated versions of their most important stories reported a significant increase in readership from immigrant communities who were previously underserved. Some cities are also experimenting with realtime translation of emergency alerts, making sure that critical information about extreme weather or health risks is available in multiple languages within minutes.\n\nThe next challenge will be building sustainable business models around these tools. Translation creates more value for readers, but it also increases editorial workload and infrastructure costs. Grants, public funding and subscriptionbased translation services are all being discussed as possible solutions. What is clear is that AIpowered translation is no longer a futuristic experiment  it is quickly becoming a core part of how local newsrooms can serve their entire community.','Newsroom Staff','Technology','en','/Assets/Images/ai-translation-transforming-local-news.jpg','2025-12-03 12:00:00'),(3,'Behind the Paywall: Why Local News Needs Subscribers to Survive','For many years, local newspapers relied almost entirely on advertising. Classifieds, print ads and inserts paid for the reporters who attended school board meetings, followed city budgets and covered high school sports. That model collapsed when ad dollars shifted to large digital platforms, leaving local outlets with shrinking newsrooms and growing coverage gaps.\n\nPaywalls and digital subscriptions are one way newsrooms are trying to rebuild a sustainable business. Instead of treating the website as a free giveaway, they present journalism as a service worth paying forjust like streaming video or music. Readers who subscribe are not simply buying access to stories; they are effectively funding the reporting itself.\n\nOf course, convincing people to pay is not easy. Many readers have become used to free content and are already paying for several entertainment subscriptions. Successful local outlets are responding with membership-style offers that include newsletters, live events, and direct ways to talk with reporters. The more a community sees its news outlet as a shared civic resource rather than just a website, the more willing people are to support it.\n\nThe longterm future of local news will likely be a mix of subscriptions, philanthropy, and limited advertising. But paywalls are playing a central role in this transition, pushing newsrooms to focus on depth, quality and trust instead of chasing page views.','Newsroom Staff','Business','en','/Assets/Images/behind-the-paywall.jpg','2025-12-03 18:01:11'),(4,'From Print to Podcast: How Newsrooms Are Repackaging Their Best Stories','The daily newspaper used to arrive only in one form: ink on paper. Today, many of the same newsrooms are experimenting with audio series, minidocumentaries and social clips that remix their reporting for new audiences. The goal is simplemeet people where they are, whether that is on a commute, at the gym or scrolling on a phone.\n\nTurning a long investigative article into a podcast, for example, allows reporters to bring in interview clips, ambient sound and narration that can make complex topics feel more personal. Some outlets are even building small audio studios inside their offices so that journalists can record updates as stories develop.\n\nThis shift does not mean that written articles are disappearing. Instead, they form the backbone of a multiformat strategy. A single reporting project might generate a feature article, an explainer video, a podcast episode and a short social post summarizing the key finding. When all of these formats link back to each other, readers can choose how deep they want to go.\n\nFor local newsrooms, the biggest challenge is capacity. Producing quality audio and video requires new skills and equipment. Many are partnering with universities or independent producers to share expertise while keeping editorial control inhouse.','Newsroom Staff','Media','en','/Assets/Images/print-to-podcast.jpg','2025-12-03 18:01:11'),(5,'Newsletters as the New Front Page','Open your email in the morning and you will likely find at least one curated newsletter summarizing the day\'s top stories. What began as a simple distribution channel has become one of the most important tools for newsrooms. Newsletters create a direct relationship with readers that is not filtered by algorithms or social media feeds.\n\nFor local outlets, this relationship is especially valuable. A welldesigned morning briefing can highlight city council decisions, weather alerts, school updates and community events in just a few paragraphs. Readers who rely on the newsletter for orientation are more likely to click through to full articles and, ultimately, to become subscribers.\n\nSuccessful newsletters balance utility and personality. They provide clear links and summaries, but they also have a recognizable voice. Many editors include short notes explaining why a story matters or how it was reported. That transparency helps build trust at a time when misinformation is easy to spread.\n\nAs inboxes become more crowded, the pressure to deliver value in every edition will only grow. Newsrooms that treat their newsletters as a core productrather than an afterthoughtare already seeing higher engagement and stronger reader loyalty.','Newsroom Staff','Product','en','/Assets/Images/newsletters-front-page.jpg','2025-12-03 18:01:11');
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
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (10,2,'ar','{\"title\":\"كيف أن الترجمة بواسطة AI هي تحويل الأخبار المحلية\",\"content\":\"وعلى مدى عقود، واجهت الصحف المحلية مشكلة بسيطة واحدة هي اللغة. وحتى عندما تكون المدينة متعددة اللغات، فإن معظم المنافذ المحلية لا تنشر إلا بلغة واحدة، مما يترك أجزاء كبيرة من السكان دون الحصول بسهولة على معلومات هامة عن السياسة والمدارس والنقل والسلامة العامة. وقد بدأت التطورات الأخيرة في ترجمة AI تغيير ذلك.\\n\\nوالنماذج الحديثة للترجمة العصبية أكثر دقة بكثير من النظم القائمة على القواعد المستخدمة في أوائل العقد الأول من الألفية. وبدلاً من ترجمة الكلمات بالكلمات، فإنها تتعلم أنماطاً من الملايين من الأحكام، مما يساعدها على فهم السياقات والأغبياء والنبرة بشكل أفضل. وهذا يعني، بالنسبة لقاعات الأنباء المحلية، أنها يمكن أن تترجم مقالات طويلة في ثواني ثم أن يكون لها محرر يستعرض بسرعة الناتج، بدلا من دفع تكاليف ترجمة بشرية كاملة من الصفر.\\n\\nغير أن ترجمة AI ليست حلاً سحرياً. ولا يزال يتعين على المحررين التحقق من الأسماء والأرقام والعبارات الحساسة ثقافيا. فبعض أنواع المحتوى، مثل قطع الرأي أو الصبر، كثيرا ما تتطلب إعادة كتابة أكثر حذرا لتجنب سوء الفهم. وهناك العديد من غرف الأنباء التي تعتمد تدفقا للعمل الهجين: وتصدر منظمة العفو الدولية مشروعاً أولياً، ويقوم الصحفيون الثنائيو اللغة أو المحررون بصقل النص قبل نشره.\\n\\nوعلى الرغم من هذه القيود، فإن الأثر على الوصول مرئي بالفعل. In several pilot projects, local outlets that introduced translated versions of their most important stories reported a significant increase in readership from immigrant communities who were previously underserved. وتختبر بعض المدن أيضا الترجمة الفورية للإنذارات الطارئة، وتأكد من أن المعلومات الحاسمة عن المخاطر الجوية أو الصحية الشديدة متاحة بلغات متعددة في غضون دقائق.\\n\\nويتمثل التحدي التالي في بناء نماذج تجارية مستدامة حول هذه الأدوات. والترجمة تخلق قيمة أكبر للقراء، ولكنها تزيد أيضا من عبء العمل التحريري وتكاليف الهياكل الأساسية. وتجري مناقشة كل من المنح والتمويل العام وخدمات الترجمة التحريرية القائمة على الاشتراكات باعتبارها حلولا ممكنة. وما هو واضح هو أن الترجمة الكهربائية لم تعد تجربة غير مجدية، فقد أصبحت بسرعة جزءا أساسيا من الكيفية التي يمكن بها لغرفة الأنباء المحلية أن تخدم مجتمعها المحلي بأسره.\"}','2025-12-03 16:33:20'),(11,2,'ja','{\"title\":\"地域ニュースを変革するAI-Powered Translationの仕組み\",\"content\":\"何十年もの間、地元の新聞は一つの簡単な問題に苦労しています。 都市が多言語化されている場合でも、ほとんどのローカルアウトレットは単一の言語でのみ公開され、政治、学校、交通機関、公共安全に関する重要な情報にアクセスしやすくすることなく、人口の大きな部分を残します。 最近のAI翻訳の進展が始まります。\\n\\n現代のニューラル翻訳モデルは、2000年代初頭のルールベースのシステムよりもはるかに正確です。 単語によって単語を翻訳するのではなく、何百万人もの文からパターンを学びます。これにより、より良いコンテキスト、イディオム、トーンをキャプチャできます。 ローカルのニュースルームでは、長い記事を秒単位で翻訳し、エディタはすぐに出力を見直し、スクラッチから完全な人間の翻訳を支払う代わりに。\\n\\nしかし、AI翻訳は魔法のソリューションではありません。 エディタは、名前、数字、文化的に敏感なフレーズをチェックする必要があります。 意見ピースやサチレなどの特定の種類のコンテンツは、誤解を避けるために、より慎重に書き直す必要があります。 多くのニュースルームは、ハイブリッドワークフローを採用しています。 AIは、出版前の文章を1つ作成し、バイリンガルジャーナリストやフリーランサーがテキストを精製します。\\n\\nこれらの制限にもかかわらず、リーチへの影響は既に表示されます。 いくつかのパイロットプロジェクトでは、翻訳されたバージョンの最も重要な物語を導入したローカルアウトレットは、以前に保存された移民コミュニティから読者層の大きな増加を報告しました。 一部の都市では、緊急アラートのリアルタイム翻訳も実験しています。極端な気象や健康リスクに関する重要な情報は、数分で複数の言語で利用できます。\\n\\n次の課題は、これらのツールの周りに持続可能なビジネスモデルを構築します。 トランスレーションは、読者にとってより価値を創出しますが、編集作業負荷とインフラコストも増加します。 助成金、公的資金およびサブスクリプションベースの翻訳サービスはすべて、可能なソリューションとして議論されています。 クリアとは、AIの翻訳はもはや未来の実験ではなく、地域のニュースルームがコミュニティ全体にどのように役立つかの根本的な部分になるということです.\"}','2025-12-03 16:33:42'),(12,3,'fr','{\"title\":\"Derrière le Paywall : Pourquoi les nouvelles locales ont besoin d\'abonnés pour survivre\",\"content\":\"Pendant de nombreuses années, les journaux locaux dépendaient presque entièrement de la publicité. petites annonces, annonces imprimées et insertions payées pour les journalistes qui ont assisté aux réunions du conseil scolaire, suivi les budgets de la ville et couvert les sports d\'école secondaire. Ce modèle s\'est effondré lorsque les fonds publicitaires ont été transférés vers de grandes plateformes numériques, laissant des points de vente locaux avec des salles de presse en baisse et des lacunes croissantes dans la couverture.\\n\\nPaywalls et abonnements numériques sont un moyen pour les salles de presse d\'essayer de reconstruire une entreprise durable. Au lieu de traiter le site comme un cadeau gratuit, ils présentent le journalisme comme un service qui vaut la peine de payer tout comme la vidéo en streaming ou la musique. Les lecteurs qui s\'abonnent ne se contentent pas d\'acheter l\'accès à des histoires; ils financent efficacement les rapports eux-mêmes.\\n\\nBien sûr, convaincre les gens de payer n\'est pas facile. Beaucoup de lecteurs se sont habitués à du contenu gratuit et paient déjà plusieurs abonnements de divertissement. Les points de vente locaux qui réussissent répondent aux offres d\'adhésion qui comprennent des bulletins d\'information, des événements en direct et des façons directes de parler avec les journalistes. Plus une communauté voit son point de presse comme une ressource civique commune plutôt qu\'un simple site Web, plus les gens sont disposés à le soutenir.\\n\\nL\'avenir à long terme des nouvelles locales sera probablement un mélange d\'abonnements, de philanthropie et de publicité limitée. Mais les paywalls jouent un rôle central dans cette transition, poussant les salles de presse à se concentrer sur la profondeur, la qualité et la confiance au lieu de chasser les pages vues.\"}','2025-12-03 18:50:44'),(13,2,'zh','{\"title\":\"AI-Powered 翻译如何转换本地新闻\",\"content\":\"数十年来,当地报纸一直奋力解决一个简单的问题:语言. 即使一个城市是多语种的,大多数地方的出入口也只用一门语言出版,使得大部分人口无法轻易地获得有关政治,学校,交通和公共安全的重要信息. 最近人工智能翻译的进展正在改变这一点。\\n\\n现代神经翻译模型远比2000年代早期使用的有章可循系统更准确. 他们不是逐字翻译,而是从上百万个句子中学习规律,这帮助他们更好地抓住上下文,平庸和语气. 对于当地的新闻室来说,这意味着他们可以在几秒钟内翻译出长篇的文章,然后让编辑迅速审查产出,而不是从零开始支付完整的人文翻译费.\\n\\n然而,AI翻译并不是一个神奇的解决方案. 编辑器仍需要检查姓名,数字和文化上敏感的短语. 某些类型的内容,如意见片或讽刺,往往需要更仔细地改写来避免误解. 许多新闻室采用混合工作流程: 大赦国际编写了初稿,双语记者或自由职业者在出版前对案文进行了完善。\\n\\n尽管存在这些局限性,但对接触的影响已经很明显。 在几个试点项目中,推出其最重要故事翻译版本的地方机构报告说,以前服务不足的移民社区的读者人数大幅增加。 一些城市还尝试实时翻译紧急警报,确保几分钟内以多种语言提供极端天气或健康风险的关键信息.\\n\\n下一个挑战是围绕这些工具建立可持续的商业模式。 翻译为读者创造了更多的价值,但也增加了编辑工作量和基础设施成本. 赠款、公共资金和基于订阅的翻译服务都正在作为可能的解决办法进行讨论。 显然,AIPower翻译已不再是一种未来实验,它正在迅速成为当地新闻室如何为整个社区服务的核心部分.\"}','2025-12-03 19:07:57'),(14,2,'fr','{\"title\":\"Comment la traduction assistée par l\'IA transforme les nouvelles locales\",\"content\":\"Pendant des décennies, les journaux locaux se sont heurtés à un problème simple : la langue. Même lorsqu\'une ville est multilingue, la plupart des points de vente locaux ne publient que dans une seule langue, laissant une grande partie de la population sans accès facile à des informations importantes sur la politique, les écoles, les transports et la sécurité publique. Les progrès récents dans la traduction de l\'intelligence artificielle commencent à changer cela.\\n\\nLes modèles modernes de traduction neurale sont beaucoup plus précis que les systèmes fondés sur des règles utilisés au début des années 2000. Au lieu de traduire mot par mot, ils apprennent des modèles de millions de phrases, ce qui les aide à mieux saisir le contexte, les idiomes et le ton. Pour les salles de presse locales, cela signifie qu\'elles peuvent traduire de longs articles en quelques secondes et ensuite avoir un éditeur rapidement passer en revue la sortie, au lieu de payer pour une traduction humaine complète à partir de zéro.\\n\\nCependant, la traduction AI n\'est pas une solution magique. Les éditeurs doivent toujours vérifier les noms, les numéros et les phrases culturellement sensibles. Certains types de contenu, comme les pièces d\'opinion ou la satire, nécessitent souvent une réécriture plus soigneuse pour éviter les malentendus. De nombreuses salles de presse adoptent un flux de travail hybride : AI produit un premier projet, et des journalistes bilingues ou des freelances peaufinent le texte avant publication.\\n\\nMalgré ces limites, l\'impact sur la portée est déjà visible. Dans plusieurs projets pilotes, les points de vente locaux qui ont présenté des versions traduites de leurs histoires les plus importantes ont signalé une augmentation importante du nombre de lecteurs provenant de communautés d\'immigrants qui étaient auparavant mal desservies. Certaines villes expérimentent également la traduction en temps réel des alertes d\'urgence, en veillant à ce que des informations critiques sur les phénomènes météorologiques extrêmes ou les risques pour la santé soient disponibles en plusieurs langues en quelques minutes.\\n\\nLe prochain défi consistera à élaborer des modèles d\'affaires durables autour de ces outils. La traduction crée plus de valeur pour les lecteurs, mais elle augmente également la charge de travail éditoriale et les coûts d\'infrastructure. Les subventions, le financement public et les services de traduction par abonnement sont tous des solutions possibles. Ce qui est clair, c\'est que la traduction assistée par l\'IA n\'est plus une expérience futuriste, elle devient rapidement un élément central de la façon dont les salles de nouvelles locales peuvent servir toute leur communauté.\"}','2025-12-03 19:08:17'),(15,2,'it','{\"title\":\"Come AI-Powered Traduzione sta trasformando le notizie locali\",\"content\":\"Per decenni, i giornali locali hanno lottato con un semplice problema: la lingua. Anche quando una città è poliglotta, la maggior parte dei punti vendita locali pubblica solo in una singola lingua, lasciando grandi parti della popolazione senza facile accesso a informazioni importanti su politica, scuole, trasporti e sicurezza pubblica. Recenti progressi nella traduzione AI stanno iniziando a cambiare questo.\\n\\nI moderni modelli di traduzione neurale sono molto più precisi dei sistemi basati sulle regole utilizzati nei primi anni 2000. Invece di tradurre parola per parola, imparano modelli da milioni di frasi, che li aiuta a meglio catturare contesto, idiomi e tono. Per le newsroom locali, questo significa che possono tradurre articoli lunghi in pochi secondi e poi avere un editor di rivedere rapidamente l\'output, invece di pagare per una traduzione umana completa da zero.\\n\\nTuttavia, la traduzione AI non è una soluzione magica. Gli editor devono ancora controllare nomi, numeri e frasi culturalmente sensibili. Alcuni tipi di contenuti, come i pezzi di opinione o la satira, richiedono spesso una riscrittura più attenta per evitare malintesi. Molte newsroom stanno adottando un flusso di lavoro ibrido: AI produce una prima bozza e giornalisti bilingue o freelance perfezionano il testo prima della pubblicazione.\\n\\nNonostante queste limitazioni, l\'impatto sulla portata è già visibile. In diversi progetti pilota, i punti vendita locali che hanno introdotto versioni tradotte delle loro storie più importanti hanno riferito un significativo aumento del lettore da parte delle comunità immigrate che erano precedentemente sottoserve. Alcune città stanno anche sperimentando la traduzione in tempo reale di avvisi di emergenza, assicurandosi che le informazioni critiche sui rischi meteorologici estremi o sanitari siano disponibili in più lingue in pochi minuti.\\n\\nLa prossima sfida sarà la costruzione di modelli di business sostenibili intorno a questi strumenti. La traduzione crea più valore per i lettori, ma aumenta anche il carico di lavoro editoriale e i costi delle infrastrutture. Le sovvenzioni, i finanziamenti pubblici e i servizi di traduzione abbonati sono tutti discussi come possibili soluzioni. Ciò che è chiaro è che la traduzione AIpowered non è più un esperimento futuristico che sta rapidamente diventando una parte fondamentale di come le newsroom locali possono servire la loro intera comunità.\"}','2025-12-03 19:08:32'),(16,5,'ar','{\"title\":\"النشرات الإخبارية بوصفها الجبهة الجديدة\",\"content\":\"افتحي بريدك الإلكتروني في الصباح وستجدين على الأقل رسالة إخبارية مشفّرة تلخص قصص اليوم ما بدأ كقناة توزيع بسيطة أصبح أحد أهم الأدوات لغرفة الأخبار وتنشئ النشرات الإخبارية علاقة مباشرة مع القراء الذين لا يغلب عليهم الخوارزميات أو وسائل الإعلام الاجتماعية.\\n\\nبالنسبة للمنافذ المحلية، هذه العلاقة قيمة بشكل خاص. ويمكن أن تبرز جلسة إحاطة صباحية مصممة تصميما جيدا قرارات مجالس المدن، وتنبيهات الطقس، وتحديث المدارس، والأحداث المجتمعية في عدد قليل فقط من الفقرات. ومن الأرجح أن ينتقل القراء الذين يعتمدون على الرسالة الإخبارية الموجهة إلى المقالات الكاملة، وأن يصبحوا في نهاية المطاف مشتركين.\\n\\nرسائل إخبارية ناجحة توازن بين الفائدة والشخصية وهي توفر وصلات وملخصات واضحة، ولكن لها أيضا صوت مشهود به. ويشتمل العديد من المحررين على مذكرات قصيرة تشرح لماذا تهم القصة أو كيفية الإبلاغ عنها. وتساعد هذه الشفافية على بناء الثقة في وقت يسهل فيه نشر المعلومات الخاطئة.\\n\\nومع تزايد ازدحام الصناديق، سيزداد الضغط على إيصال القيمة في كل طبعة. غرف الأخبار التي تعامل رسائلها الإخبارية على أنها منتجة أساسية أكثر من النظرة بعد ذلك.\"}','2025-12-03 20:22:18'),(17,2,'de','{\"title\":\"Wie KI-Powered-Übersetzung lokale Nachrichten transformiert\",\"content\":\"Seit Jahrzehnten haben lokale Zeitungen mit einem einfachen Problem gekämpft: Sprache. Auch wenn eine Stadt mehrsprachig ist, veröffentlichen die meisten lokalen Steckdosen nur in einer einzigen Sprache, so dass große Teile der Bevölkerung ohne einfachen Zugang zu wichtigen Informationen über Politik, Schulen, Transport und öffentliche Sicherheit. Die jüngsten Fortschritte in der KI-Übersetzung beginnen, das zu ändern.\\n\\nModerne neurale Translationsmodelle sind viel genauer als die in den frühen 2000er Jahren verwendeten regelbasierten Systeme. Anstatt Wort nach Wort zu übersetzen, lernen sie Muster aus Millionen von Sätzen, die ihnen helfen, Kontext, Idiome und Ton besser zu erfassen. Für lokale Newsrooms bedeutet das, dass sie lange Artikel in Sekunden übersetzen können und dann einen Editor schnell die Ausgabe überprüfen, anstatt für eine vollständige menschliche Übersetzung von Grund auf zu zahlen.\\n\\nKI-Übersetzung ist jedoch keine magische Lösung. Editoren müssen immer noch Namen, Zahlen und kulturell sensible Phrasen überprüfen. Bestimmte Arten von Inhalten, wie Meinungsstücke oder Satire, erfordern oft eine sorgfältigere Neuschrift, um Missverständnisse zu vermeiden. Viele Newsrooms übernehmen einen hybriden Workflow: AI produziert einen ersten Entwurf, und zweisprachige Journalisten oder Freiberufler verfeinern den Text vor der Veröffentlichung.\\n\\nTrotz dieser Einschränkungen ist der Einfluss auf die Reichweite bereits sichtbar. In mehreren Pilotprojekten berichteten lokale Verkaufsstellen, die übersetzte Versionen ihrer wichtigsten Geschichten eingeführt hatten, eine signifikante Zunahme der Leserschaft von Immigrantengemeinschaften, die zuvor unterbewertet wurden. Einige Städte experimentieren auch mit Echtzeit-Übersetzung von Notfallalarmen, um sicherzustellen, dass kritische Informationen über extreme Wetter- oder Gesundheitsrisiken innerhalb von Minuten in mehreren Sprachen verfügbar sind.\\n\\nDie nächste Herausforderung wird es sein, nachhaltige Geschäftsmodelle um diese Werkzeuge zu bauen. Übersetzung schafft mehr Wert für Leser, aber es erhöht auch redaktionelle Arbeitsbelastung und Infrastrukturkosten. Zuwendungen, öffentliche Förder- und Subskriptionsdienste werden als mögliche Lösungen diskutiert. Es ist klar, dass AIpowered Translation nicht mehr ein futuristisches Experiment ist, es wird schnell ein Kernteil davon, wie lokale Newsrooms ihre ganze Gemeinschaft bedienen können.\"}','2025-12-03 20:30:26'),(18,2,'ko','{\"title\":\"AI-Powered Translation는 현지 뉴스를 전달하는 방법\",\"content\":\"수십 년 동안 현지 신문은 하나의 간단한 문제로 투쟁했습니다. 언어. 도시가 다언어일 때, 대부분의 로컬 아울렛은 단일 언어로 출판되며, 정치, 학교, 교통 및 공공 안전에 대한 중요한 정보에 접근하지 않고 인구의 큰 부분을 떠나고 있습니다. AI 번역의 최근 발전은 그 변경을 시작합니다.\\n\\n현대 신경 번역 모델은 초기 2000 년대에 사용되는 룰 기반 시스템보다 훨씬 정확합니다. 단어로 단어를 번역하는 대신, 그들은 더 나은 캡처 컨텍스트, idioms 및 톤을 돕는 수백만 문장에서 패턴을 배울. 로컬 뉴스룸의 경우, 이것은 그들이 몇 초안에 긴 기사를 번역할 수 있고 그 후에 편집자는 빨리 찰상에서 가득 차있는 인간적인 번역을 위해 지불하는 대신 산출을 검토합니다.\\n\\n그러나 AI 번역은 마술 솔루션이 아닙니다. 편집기는 여전히 이름, 숫자 및 문화적으로 민감한 구문을 확인해야합니다. 특정 유형의 내용, 같은 의견 조각 또는 satire, 종종 더 많은 주의적인 rewriting to avoid misunderstanding. 많은 뉴스룸은 하이브리드 워크플로우를 채택하고 있습니다. AI는 첫 번째 초안과 이중 언어 기자 또는 프리랜서가 출판하기 전에 텍스트를 정제합니다.\\n\\n이러한 제한에도 불구하고, 도달에 미치는 영향은 이미 볼 수 있습니다. 몇 가지 파일럿 프로젝트에서, 그들의 가장 중요한 이야기의 번역 된 버전을 도입 한 지역 출구는 이전에 보존 된 이민 커뮤니티의 독자적인 증가를보고. 일부 도시는 긴급 경고의 실시간 번역으로 실험하고, 극단적 인 날씨 또는 건강 위험에 대한 중요한 정보가 몇 분 안에 여러 언어로 제공됩니다.\\n\\n다음 도전은 이러한 도구의 지속 가능한 비즈니스 모델을 구축 할 것입니다. 번역은 독자들에게 더 많은 가치를 창출하지만, 에디터의 워크로드와 인프라 비용을 증가시킵니다. Grants, public funding and subscriptionbased translation services are all being discussed as possible solution. 분명히 AIpowered 번역은 더 이상 미래 실험이 아니라 로컬 뉴스룸이 전체 커뮤니티를 봉사 할 수있는 핵심 부분이 될 것입니다.\"}','2025-12-03 20:30:50');
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'7azem','7azem0.0hassan@gmail.com','$2y$10$sXI9mIsvDjHve2QBcE9nKORtxr6pummTEBhH3cHI657eLsixmy7Ua','2025-11-20 13:46:45'),(5,'6azem','7azem00.0hassan@gmail.com','$2y$10$6hDKl.7OMstpEzk0zVLeWeCDTA48oG3BgH.uS1f6x6CJFJX9tHor.','2025-11-20 13:57:20'),(6,'5azem','7azem00.00hassan@gmail.com','$2y$10$3u5fQYgeDqJRTCTsexTkSOwgWw8j6pmekwPO/qK7sY2GNpt4k1ika','2025-11-20 13:57:56');
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

-- Dump completed on 2025-12-03 21:31:46
