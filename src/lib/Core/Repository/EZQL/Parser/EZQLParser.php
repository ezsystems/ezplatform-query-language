<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser {
    use Antlr\Antlr4\Runtime\Atn\ATN;
    use Antlr\Antlr4\Runtime\Atn\ATNDeserializer;
    use Antlr\Antlr4\Runtime\Atn\ParserATNSimulator;
    use Antlr\Antlr4\Runtime\Dfa\DFA;
    use Antlr\Antlr4\Runtime\Error\Exceptions\FailedPredicateException;
    use Antlr\Antlr4\Runtime\Error\Exceptions\NoViableAltException;
    use Antlr\Antlr4\Runtime\PredictionContexts\PredictionContextCache;
    use Antlr\Antlr4\Runtime\Error\Exceptions\RecognitionException;
    use Antlr\Antlr4\Runtime\RuleContext;
    use Antlr\Antlr4\Runtime\Token;
    use Antlr\Antlr4\Runtime\TokenStream;
    use Antlr\Antlr4\Runtime\Vocabulary;
    use Antlr\Antlr4\Runtime\VocabularyImpl;
    use Antlr\Antlr4\Runtime\Parser;

    final class EZQLParser extends Parser
    {
        public const T__0 = 1;
        public const T__1 = 2;
        public const T__2 = 3;
        public const T__3 = 4;
        public const T__4 = 5;
        public const K_ALL = 6;
        public const K_ANCESTOR = 7;
        public const K_AND = 8;
        public const K_ASC = 9;
        public const K_BETWEEN = 10;
        public const K_BOOST = 11;
        public const K_BY = 12;
        public const K_CONTAINS = 13;
        public const K_CONTENT = 14;
        public const K_CODE = 15;
        public const K_CREATED = 16;
        public const K_DESC = 17;
        public const K_DEPTH = 18;
        public const K_EMPTY = 19;
        public const K_FALSE = 20;
        public const K_FULLTEXT = 21;
        public const K_FUZZINESS = 22;
        public const K_FIELD = 23;
        public const K_FILTER = 24;
        public const K_GROUP = 25;
        public const K_HIDDEN = 26;
        public const K_ID = 27;
        public const K_IDENTIFIER = 28;
        public const K_IN = 29;
        public const K_INFO = 30;
        public const K_IS = 31;
        public const K_LANGUAGE = 32;
        public const K_LIKE = 33;
        public const K_LIMIT = 34;
        public const K_LOCATION = 35;
        public const K_MAIN = 36;
        public const K_MATCH = 37;
        public const K_MODIFIER = 38;
        public const K_MODIFIED = 39;
        public const K_NONE = 40;
        public const K_NOT = 41;
        public const K_OBJECT = 42;
        public const K_OFFSET = 43;
        public const K_OR = 44;
        public const K_ORDER = 45;
        public const K_OWNER = 46;
        public const K_PARENT = 47;
        public const K_PRIORITY = 48;
        public const K_REMOTE = 49;
        public const K_RELATION = 50;
        public const K_STATE = 51;
        public const K_QUERY = 52;
        public const K_SECTION = 53;
        public const K_SUBTREE = 54;
        public const K_SELECT = 55;
        public const K_TRUE = 56;
        public const K_TYPE = 57;
        public const K_VISIBLE = 58;
        public const EQ = 59;
        public const NEQ = 60;
        public const GT = 61;
        public const GTE = 62;
        public const LT = 63;
        public const LTE = 64;
        public const INT = 65;
        public const DOUBLE = 66;
        public const STRING = 67;
        public const PARAMETER_NAME = 68;
        public const ID = 69;
        public const WS = 70;

        public const RULE_stmt = 0;
        public const RULE_selectLocation = 1;
        public const RULE_selectContent = 2;
        public const RULE_selectContentInfo = 3;
        public const RULE_selectProperties = 4;
        public const RULE_sortClauseList = 5;
        public const RULE_sortClause = 6;
        public const RULE_sortOrder = 7;
        public const RULE_expr = 8;
        public const RULE_operator = 9;
        public const RULE_fuzziness = 10;
        public const RULE_boosting = 11;
        public const RULE_fieldBoost = 12;
        public const RULE_value = 13;
        public const RULE_argumentRange = 14;
        public const RULE_argumentList = 15;
        public const RULE_argument = 16;
        public const RULE_parameter = 17;
        public const RULE_scalar = 18;
        public const RULE_userMetadataTarget = 19;

        /**
         * @var array<string>
         */
        public const RULE_NAMES = [
            'stmt', 'selectLocation', 'selectContent', 'selectContentInfo', 'selectProperties',
            'sortClauseList', 'sortClause', 'sortOrder', 'expr', 'operator', 'fuzziness',
            'boosting', 'fieldBoost', 'value', 'argumentRange', 'argumentList', 'argument',
            'parameter', 'scalar', 'userMetadataTarget',
        ];

        /**
         * @var array<string|null>
         */
        private const LITERAL_NAMES = [
            null, "','", "'('", "')'", "'^'", "'..'", null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, "'='", "'!='", "'>'", "'>='", "'<'",
            "'<='",
        ];

        /**
         * @var array<string>
         */
        private const SYMBOLIC_NAMES = [
            null, null, null, null, null, null, 'K_ALL', 'K_ANCESTOR', 'K_AND',
            'K_ASC', 'K_BETWEEN', 'K_BOOST', 'K_BY', 'K_CONTAINS', 'K_CONTENT',
            'K_CODE', 'K_CREATED', 'K_DESC', 'K_DEPTH', 'K_EMPTY', 'K_FALSE',
            'K_FULLTEXT', 'K_FUZZINESS', 'K_FIELD', 'K_FILTER', 'K_GROUP', 'K_HIDDEN',
            'K_ID', 'K_IDENTIFIER', 'K_IN', 'K_INFO', 'K_IS', 'K_LANGUAGE', 'K_LIKE',
            'K_LIMIT', 'K_LOCATION', 'K_MAIN', 'K_MATCH', 'K_MODIFIER', 'K_MODIFIED',
            'K_NONE', 'K_NOT', 'K_OBJECT', 'K_OFFSET', 'K_OR', 'K_ORDER', 'K_OWNER',
            'K_PARENT', 'K_PRIORITY', 'K_REMOTE', 'K_RELATION', 'K_STATE', 'K_QUERY',
            'K_SECTION', 'K_SUBTREE', 'K_SELECT', 'K_TRUE', 'K_TYPE', 'K_VISIBLE',
            'EQ', 'NEQ', 'GT', 'GTE', 'LT', 'LTE', 'INT', 'DOUBLE', 'STRING',
            'PARAMETER_NAME', 'ID', 'WS',
        ];

        /**
         * @var string
         */
        private const SERIALIZED_ATN =
            "\u{3}\u{608B}\u{A72A}\u{8133}\u{B9ED}\u{417C}\u{3BE7}\u{7786}\u{5964}" .
            "\u{3}\u{48}\u{12C}\u{4}\u{2}\u{9}\u{2}\u{4}\u{3}\u{9}\u{3}\u{4}\u{4}" .
            "\u{9}\u{4}\u{4}\u{5}\u{9}\u{5}\u{4}\u{6}\u{9}\u{6}\u{4}\u{7}\u{9}" .
            "\u{7}\u{4}\u{8}\u{9}\u{8}\u{4}\u{9}\u{9}\u{9}\u{4}\u{A}\u{9}\u{A}" .
            "\u{4}\u{B}\u{9}\u{B}\u{4}\u{C}\u{9}\u{C}\u{4}\u{D}\u{9}\u{D}\u{4}" .
            "\u{E}\u{9}\u{E}\u{4}\u{F}\u{9}\u{F}\u{4}\u{10}\u{9}\u{10}\u{4}\u{11}" .
            "\u{9}\u{11}\u{4}\u{12}\u{9}\u{12}\u{4}\u{13}\u{9}\u{13}\u{4}\u{14}" .
            "\u{9}\u{14}\u{4}\u{15}\u{9}\u{15}\u{3}\u{2}\u{3}\u{2}\u{3}\u{2}\u{5}" .
            "\u{2}\u{2E}\u{A}\u{2}\u{3}\u{3}\u{3}\u{3}\u{3}\u{3}\u{3}\u{3}\u{3}" .
            "\u{4}\u{3}\u{4}\u{3}\u{4}\u{3}\u{4}\u{3}\u{5}\u{3}\u{5}\u{3}\u{5}" .
            "\u{3}\u{5}\u{3}\u{5}\u{3}\u{6}\u{3}\u{6}\u{3}\u{6}\u{5}\u{6}\u{40}" .
            "\u{A}\u{6}\u{3}\u{6}\u{3}\u{6}\u{5}\u{6}\u{44}\u{A}\u{6}\u{3}\u{6}" .
            "\u{3}\u{6}\u{3}\u{6}\u{5}\u{6}\u{49}\u{A}\u{6}\u{3}\u{6}\u{3}\u{6}" .
            "\u{5}\u{6}\u{4D}\u{A}\u{6}\u{3}\u{6}\u{3}\u{6}\u{5}\u{6}\u{51}\u{A}" .
            "\u{6}\u{3}\u{7}\u{3}\u{7}\u{3}\u{7}\u{7}\u{7}\u{56}\u{A}\u{7}\u{C}" .
            "\u{7}\u{E}\u{7}\u{59}\u{B}\u{7}\u{3}\u{8}\u{3}\u{8}\u{3}\u{8}\u{3}" .
            "\u{9}\u{3}\u{9}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{5}\u{A}\u{97}\u{A}" .
            "\u{A}\u{3}\u{A}\u{5}\u{A}\u{9A}\u{A}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}" .
            "\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{5}\u{A}\u{E7}\u{A}\u{A}\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}" .
            "\u{3}\u{A}\u{3}\u{A}\u{3}\u{A}\u{7}\u{A}\u{EF}\u{A}\u{A}\u{C}\u{A}" .
            "\u{E}\u{A}\u{F2}\u{B}\u{A}\u{3}\u{B}\u{3}\u{B}\u{3}\u{B}\u{3}\u{B}" .
            "\u{5}\u{B}\u{F8}\u{A}\u{B}\u{3}\u{C}\u{3}\u{C}\u{3}\u{C}\u{3}\u{D}" .
            "\u{3}\u{D}\u{3}\u{D}\u{3}\u{D}\u{7}\u{D}\u{101}\u{A}\u{D}\u{C}\u{D}" .
            "\u{E}\u{D}\u{104}\u{B}\u{D}\u{3}\u{E}\u{3}\u{E}\u{3}\u{E}\u{3}\u{E}" .
            "\u{3}\u{F}\u{3}\u{F}\u{3}\u{F}\u{5}\u{F}\u{10D}\u{A}\u{F}\u{3}\u{10}" .
            "\u{3}\u{10}\u{3}\u{10}\u{3}\u{10}\u{3}\u{11}\u{3}\u{11}\u{3}\u{11}" .
            "\u{3}\u{11}\u{7}\u{11}\u{117}\u{A}\u{11}\u{C}\u{11}\u{E}\u{11}\u{11A}" .
            "\u{B}\u{11}\u{3}\u{11}\u{3}\u{11}\u{3}\u{12}\u{3}\u{12}\u{5}\u{12}" .
            "\u{120}\u{A}\u{12}\u{3}\u{13}\u{3}\u{13}\u{3}\u{14}\u{3}\u{14}\u{3}" .
            "\u{14}\u{3}\u{14}\u{5}\u{14}\u{128}\u{A}\u{14}\u{3}\u{15}\u{3}\u{15}" .
            "\u{3}\u{15}\u{2}\u{3}\u{12}\u{16}\u{2}\u{4}\u{6}\u{8}\u{A}\u{C}\u{E}" .
            "\u{10}\u{12}\u{14}\u{16}\u{18}\u{1A}\u{1C}\u{1E}\u{20}\u{22}\u{24}" .
            "\u{26}\u{28}\u{2}\u{7}\u{4}\u{2}\u{B}\u{B}\u{13}\u{13}\u{4}\u{2}\u{1C}" .
            "\u{1C}\u{3C}\u{3C}\u{8}\u{2}\u{C}\u{C}\u{F}\u{F}\u{1F}\u{1F}\u{23}" .
            "\u{23}\u{3D}\u{3D}\u{3F}\u{42}\u{4}\u{2}\u{16}\u{16}\u{3A}\u{3A}\u{5}" .
            "\u{2}\u{1B}\u{1B}\u{28}\u{28}\u{30}\u{30}\u{2}\u{149}\u{2}\u{2D}\u{3}" .
            "\u{2}\u{2}\u{2}\u{4}\u{2F}\u{3}\u{2}\u{2}\u{2}\u{6}\u{33}\u{3}\u{2}" .
            "\u{2}\u{2}\u{8}\u{37}\u{3}\u{2}\u{2}\u{2}\u{A}\u{3F}\u{3}\u{2}\u{2}" .
            "\u{2}\u{C}\u{52}\u{3}\u{2}\u{2}\u{2}\u{E}\u{5A}\u{3}\u{2}\u{2}\u{2}" .
            "\u{10}\u{5D}\u{3}\u{2}\u{2}\u{2}\u{12}\u{E6}\u{3}\u{2}\u{2}\u{2}\u{14}" .
            "\u{F7}\u{3}\u{2}\u{2}\u{2}\u{16}\u{F9}\u{3}\u{2}\u{2}\u{2}\u{18}\u{FC}" .
            "\u{3}\u{2}\u{2}\u{2}\u{1A}\u{105}\u{3}\u{2}\u{2}\u{2}\u{1C}\u{10C}" .
            "\u{3}\u{2}\u{2}\u{2}\u{1E}\u{10E}\u{3}\u{2}\u{2}\u{2}\u{20}\u{112}" .
            "\u{3}\u{2}\u{2}\u{2}\u{22}\u{11F}\u{3}\u{2}\u{2}\u{2}\u{24}\u{121}" .
            "\u{3}\u{2}\u{2}\u{2}\u{26}\u{127}\u{3}\u{2}\u{2}\u{2}\u{28}\u{129}" .
            "\u{3}\u{2}\u{2}\u{2}\u{2A}\u{2E}\u{5}\u{4}\u{3}\u{2}\u{2B}\u{2E}\u{5}" .
            "\u{6}\u{4}\u{2}\u{2C}\u{2E}\u{5}\u{8}\u{5}\u{2}\u{2D}\u{2A}\u{3}\u{2}" .
            "\u{2}\u{2}\u{2D}\u{2B}\u{3}\u{2}\u{2}\u{2}\u{2D}\u{2C}\u{3}\u{2}\u{2}" .
            "\u{2}\u{2E}\u{3}\u{3}\u{2}\u{2}\u{2}\u{2F}\u{30}\u{7}\u{39}\u{2}\u{2}" .
            "\u{30}\u{31}\u{7}\u{25}\u{2}\u{2}\u{31}\u{32}\u{5}\u{A}\u{6}\u{2}" .
            "\u{32}\u{5}\u{3}\u{2}\u{2}\u{2}\u{33}\u{34}\u{7}\u{39}\u{2}\u{2}\u{34}" .
            "\u{35}\u{7}\u{10}\u{2}\u{2}\u{35}\u{36}\u{5}\u{A}\u{6}\u{2}\u{36}" .
            "\u{7}\u{3}\u{2}\u{2}\u{2}\u{37}\u{38}\u{7}\u{39}\u{2}\u{2}\u{38}\u{39}" .
            "\u{7}\u{10}\u{2}\u{2}\u{39}\u{3A}\u{7}\u{20}\u{2}\u{2}\u{3A}\u{3B}" .
            "\u{5}\u{A}\u{6}\u{2}\u{3B}\u{9}\u{3}\u{2}\u{2}\u{2}\u{3C}\u{3D}\u{7}" .
            "\u{1A}\u{2}\u{2}\u{3D}\u{3E}\u{7}\u{E}\u{2}\u{2}\u{3E}\u{40}\u{5}" .
            "\u{12}\u{A}\u{2}\u{3F}\u{3C}\u{3}\u{2}\u{2}\u{2}\u{3F}\u{40}\u{3}" .
            "\u{2}\u{2}\u{2}\u{40}\u{43}\u{3}\u{2}\u{2}\u{2}\u{41}\u{42}\u{7}\u{36}" .
            "\u{2}\u{2}\u{42}\u{44}\u{5}\u{12}\u{A}\u{2}\u{43}\u{41}\u{3}\u{2}" .
            "\u{2}\u{2}\u{43}\u{44}\u{3}\u{2}\u{2}\u{2}\u{44}\u{48}\u{3}\u{2}\u{2}" .
            "\u{2}\u{45}\u{46}\u{7}\u{2F}\u{2}\u{2}\u{46}\u{47}\u{7}\u{E}\u{2}" .
            "\u{2}\u{47}\u{49}\u{5}\u{C}\u{7}\u{2}\u{48}\u{45}\u{3}\u{2}\u{2}\u{2}" .
            "\u{48}\u{49}\u{3}\u{2}\u{2}\u{2}\u{49}\u{4C}\u{3}\u{2}\u{2}\u{2}\u{4A}" .
            "\u{4B}\u{7}\u{24}\u{2}\u{2}\u{4B}\u{4D}\u{5}\u{22}\u{12}\u{2}\u{4C}" .
            "\u{4A}\u{3}\u{2}\u{2}\u{2}\u{4C}\u{4D}\u{3}\u{2}\u{2}\u{2}\u{4D}\u{50}" .
            "\u{3}\u{2}\u{2}\u{2}\u{4E}\u{4F}\u{7}\u{2D}\u{2}\u{2}\u{4F}\u{51}" .
            "\u{5}\u{22}\u{12}\u{2}\u{50}\u{4E}\u{3}\u{2}\u{2}\u{2}\u{50}\u{51}" .
            "\u{3}\u{2}\u{2}\u{2}\u{51}\u{B}\u{3}\u{2}\u{2}\u{2}\u{52}\u{57}\u{5}" .
            "\u{E}\u{8}\u{2}\u{53}\u{54}\u{7}\u{3}\u{2}\u{2}\u{54}\u{56}\u{5}\u{E}" .
            "\u{8}\u{2}\u{55}\u{53}\u{3}\u{2}\u{2}\u{2}\u{56}\u{59}\u{3}\u{2}\u{2}" .
            "\u{2}\u{57}\u{55}\u{3}\u{2}\u{2}\u{2}\u{57}\u{58}\u{3}\u{2}\u{2}\u{2}" .
            "\u{58}\u{D}\u{3}\u{2}\u{2}\u{2}\u{59}\u{57}\u{3}\u{2}\u{2}\u{2}\u{5A}" .
            "\u{5B}\u{7}\u{47}\u{2}\u{2}\u{5B}\u{5C}\u{5}\u{10}\u{9}\u{2}\u{5C}" .
            "\u{F}\u{3}\u{2}\u{2}\u{2}\u{5D}\u{5E}\u{9}\u{2}\u{2}\u{2}\u{5E}\u{11}" .
            "\u{3}\u{2}\u{2}\u{2}\u{5F}\u{60}\u{8}\u{A}\u{1}\u{2}\u{60}\u{61}\u{7}" .
            "\u{27}\u{2}\u{2}\u{61}\u{E7}\u{7}\u{8}\u{2}\u{2}\u{62}\u{63}\u{7}" .
            "\u{27}\u{2}\u{2}\u{63}\u{E7}\u{7}\u{2A}\u{2}\u{2}\u{64}\u{65}\u{7}" .
            "\u{21}\u{2}\u{2}\u{65}\u{66}\u{7}\u{26}\u{2}\u{2}\u{66}\u{E7}\u{7}" .
            "\u{25}\u{2}\u{2}\u{67}\u{68}\u{7}\u{21}\u{2}\u{2}\u{68}\u{69}\u{7}" .
            "\u{2B}\u{2}\u{2}\u{69}\u{6A}\u{7}\u{26}\u{2}\u{2}\u{6A}\u{E7}\u{7}" .
            "\u{25}\u{2}\u{2}\u{6B}\u{6C}\u{7}\u{21}\u{2}\u{2}\u{6C}\u{E7}\u{9}" .
            "\u{3}\u{2}\u{2}\u{6D}\u{6E}\u{7}\u{19}\u{2}\u{2}\u{6E}\u{6F}\u{7}" .
            "\u{47}\u{2}\u{2}\u{6F}\u{70}\u{7}\u{21}\u{2}\u{2}\u{70}\u{E7}\u{7}" .
            "\u{15}\u{2}\u{2}\u{71}\u{72}\u{7}\u{19}\u{2}\u{2}\u{72}\u{73}\u{7}" .
            "\u{47}\u{2}\u{2}\u{73}\u{74}\u{7}\u{21}\u{2}\u{2}\u{74}\u{75}\u{7}" .
            "\u{2B}\u{2}\u{2}\u{75}\u{E7}\u{7}\u{15}\u{2}\u{2}\u{76}\u{77}\u{7}" .
            "\u{19}\u{2}\u{2}\u{77}\u{78}\u{7}\u{47}\u{2}\u{2}\u{78}\u{79}\u{5}" .
            "\u{14}\u{B}\u{2}\u{79}\u{7A}\u{5}\u{1C}\u{F}\u{2}\u{7A}\u{E7}\u{3}" .
            "\u{2}\u{2}\u{2}\u{7B}\u{7C}\u{7}\u{19}\u{2}\u{2}\u{7C}\u{7D}\u{7}" .
            "\u{34}\u{2}\u{2}\u{7D}\u{7E}\u{7}\u{47}\u{2}\u{2}\u{7E}\u{7F}\u{5}" .
            "\u{14}\u{B}\u{2}\u{7F}\u{80}\u{5}\u{1C}\u{F}\u{2}\u{80}\u{E7}\u{3}" .
            "\u{2}\u{2}\u{2}\u{81}\u{82}\u{7}\u{25}\u{2}\u{2}\u{82}\u{83}\u{7}" .
            "\u{32}\u{2}\u{2}\u{83}\u{84}\u{5}\u{14}\u{B}\u{2}\u{84}\u{85}\u{5}" .
            "\u{1C}\u{F}\u{2}\u{85}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{86}\u{87}\u{7}" .
            "\u{25}\u{2}\u{2}\u{87}\u{88}\u{7}\u{14}\u{2}\u{2}\u{88}\u{89}\u{5}" .
            "\u{14}\u{B}\u{2}\u{89}\u{8A}\u{5}\u{1C}\u{F}\u{2}\u{8A}\u{E7}\u{3}" .
            "\u{2}\u{2}\u{2}\u{8B}\u{8C}\u{7}\u{12}\u{2}\u{2}\u{8C}\u{8D}\u{5}" .
            "\u{14}\u{B}\u{2}\u{8D}\u{8E}\u{5}\u{1C}\u{F}\u{2}\u{8E}\u{E7}\u{3}" .
            "\u{2}\u{2}\u{2}\u{8F}\u{90}\u{7}\u{29}\u{2}\u{2}\u{90}\u{91}\u{5}" .
            "\u{14}\u{B}\u{2}\u{91}\u{92}\u{5}\u{1C}\u{F}\u{2}\u{92}\u{E7}\u{3}" .
            "\u{2}\u{2}\u{2}\u{93}\u{94}\u{7}\u{17}\u{2}\u{2}\u{94}\u{96}\u{5}" .
            "\u{1C}\u{F}\u{2}\u{95}\u{97}\u{5}\u{16}\u{C}\u{2}\u{96}\u{95}\u{3}" .
            "\u{2}\u{2}\u{2}\u{96}\u{97}\u{3}\u{2}\u{2}\u{2}\u{97}\u{99}\u{3}\u{2}" .
            "\u{2}\u{2}\u{98}\u{9A}\u{5}\u{18}\u{D}\u{2}\u{99}\u{98}\u{3}\u{2}" .
            "\u{2}\u{2}\u{99}\u{9A}\u{3}\u{2}\u{2}\u{2}\u{9A}\u{E7}\u{3}\u{2}\u{2}" .
            "\u{2}\u{9B}\u{9C}\u{5}\u{28}\u{15}\u{2}\u{9C}\u{9D}\u{5}\u{14}\u{B}" .
            "\u{2}\u{9D}\u{9E}\u{5}\u{1C}\u{F}\u{2}\u{9E}\u{E7}\u{3}\u{2}\u{2}" .
            "\u{2}\u{9F}\u{A0}\u{7}\u{9}\u{2}\u{2}\u{A0}\u{A1}\u{5}\u{14}\u{B}" .
            "\u{2}\u{A1}\u{A2}\u{5}\u{1C}\u{F}\u{2}\u{A2}\u{E7}\u{3}\u{2}\u{2}" .
            "\u{2}\u{A3}\u{A4}\u{7}\u{10}\u{2}\u{2}\u{A4}\u{A5}\u{7}\u{1D}\u{2}" .
            "\u{2}\u{A5}\u{A6}\u{5}\u{14}\u{B}\u{2}\u{A6}\u{A7}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{A7}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{A8}\u{A9}\u{7}\u{10}\u{2}" .
            "\u{2}\u{A9}\u{AA}\u{7}\u{3B}\u{2}\u{2}\u{AA}\u{AB}\u{7}\u{1D}\u{2}" .
            "\u{2}\u{AB}\u{AC}\u{5}\u{14}\u{B}\u{2}\u{AC}\u{AD}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{AD}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{AE}\u{AF}\u{7}\u{10}\u{2}" .
            "\u{2}\u{AF}\u{B0}\u{7}\u{3B}\u{2}\u{2}\u{B0}\u{B1}\u{7}\u{1E}\u{2}" .
            "\u{2}\u{B1}\u{B2}\u{5}\u{14}\u{B}\u{2}\u{B2}\u{B3}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{B3}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{B4}\u{B5}\u{7}\u{22}\u{2}" .
            "\u{2}\u{B5}\u{B6}\u{7}\u{11}\u{2}\u{2}\u{B6}\u{B7}\u{5}\u{14}\u{B}" .
            "\u{2}\u{B7}\u{B8}\u{5}\u{1C}\u{F}\u{2}\u{B8}\u{E7}\u{3}\u{2}\u{2}" .
            "\u{2}\u{B9}\u{BA}\u{7}\u{25}\u{2}\u{2}\u{BA}\u{BB}\u{7}\u{1D}\u{2}" .
            "\u{2}\u{BB}\u{BC}\u{5}\u{14}\u{B}\u{2}\u{BC}\u{BD}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{BD}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{BE}\u{BF}\u{7}\u{25}\u{2}" .
            "\u{2}\u{BF}\u{C0}\u{7}\u{33}\u{2}\u{2}\u{C0}\u{C1}\u{7}\u{1D}\u{2}" .
            "\u{2}\u{C1}\u{C2}\u{5}\u{14}\u{B}\u{2}\u{C2}\u{C3}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{C3}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{C4}\u{C5}\u{7}\u{2C}\u{2}" .
            "\u{2}\u{C5}\u{C6}\u{7}\u{35}\u{2}\u{2}\u{C6}\u{C7}\u{7}\u{1D}\u{2}" .
            "\u{2}\u{C7}\u{C8}\u{5}\u{14}\u{B}\u{2}\u{C8}\u{C9}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{C9}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{CA}\u{CB}\u{7}\u{31}\u{2}" .
            "\u{2}\u{CB}\u{CC}\u{7}\u{25}\u{2}\u{2}\u{CC}\u{CD}\u{7}\u{1D}\u{2}" .
            "\u{2}\u{CD}\u{CE}\u{5}\u{14}\u{B}\u{2}\u{CE}\u{CF}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{CF}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{D0}\u{D1}\u{7}\u{33}\u{2}" .
            "\u{2}\u{D1}\u{D2}\u{7}\u{1D}\u{2}\u{2}\u{D2}\u{D3}\u{5}\u{14}\u{B}" .
            "\u{2}\u{D3}\u{D4}\u{5}\u{1C}\u{F}\u{2}\u{D4}\u{E7}\u{3}\u{2}\u{2}" .
            "\u{2}\u{D5}\u{D6}\u{7}\u{37}\u{2}\u{2}\u{D6}\u{D7}\u{7}\u{1D}\u{2}" .
            "\u{2}\u{D7}\u{D8}\u{5}\u{14}\u{B}\u{2}\u{D8}\u{D9}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{D9}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{DA}\u{DB}\u{7}\u{38}\u{2}" .
            "\u{2}\u{DB}\u{DC}\u{5}\u{14}\u{B}\u{2}\u{DC}\u{DD}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{DD}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{DE}\u{DF}\u{7}\u{47}\u{2}" .
            "\u{2}\u{DF}\u{E0}\u{5}\u{14}\u{B}\u{2}\u{E0}\u{E1}\u{5}\u{1C}\u{F}" .
            "\u{2}\u{E1}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{E2}\u{E3}\u{7}\u{4}\u{2}\u{2}" .
            "\u{E3}\u{E4}\u{5}\u{12}\u{A}\u{2}\u{E4}\u{E5}\u{7}\u{5}\u{2}\u{2}" .
            "\u{E5}\u{E7}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{5F}\u{3}\u{2}\u{2}\u{2}\u{E6}" .
            "\u{62}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{64}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{67}" .
            "\u{3}\u{2}\u{2}\u{2}\u{E6}\u{6B}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{6D}\u{3}" .
            "\u{2}\u{2}\u{2}\u{E6}\u{71}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{76}\u{3}\u{2}" .
            "\u{2}\u{2}\u{E6}\u{7B}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{81}\u{3}\u{2}\u{2}" .
            "\u{2}\u{E6}\u{86}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{8B}\u{3}\u{2}\u{2}\u{2}" .
            "\u{E6}\u{8F}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{93}\u{3}\u{2}\u{2}\u{2}\u{E6}" .
            "\u{9B}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{9F}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{A3}" .
            "\u{3}\u{2}\u{2}\u{2}\u{E6}\u{A8}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{AE}\u{3}" .
            "\u{2}\u{2}\u{2}\u{E6}\u{B4}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{B9}\u{3}\u{2}" .
            "\u{2}\u{2}\u{E6}\u{BE}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{C4}\u{3}\u{2}\u{2}" .
            "\u{2}\u{E6}\u{CA}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{D0}\u{3}\u{2}\u{2}\u{2}" .
            "\u{E6}\u{D5}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{DA}\u{3}\u{2}\u{2}\u{2}\u{E6}" .
            "\u{DE}\u{3}\u{2}\u{2}\u{2}\u{E6}\u{E2}\u{3}\u{2}\u{2}\u{2}\u{E7}\u{F0}" .
            "\u{3}\u{2}\u{2}\u{2}\u{E8}\u{E9}\u{C}\u{5}\u{2}\u{2}\u{E9}\u{EA}\u{7}" .
            "\u{A}\u{2}\u{2}\u{EA}\u{EF}\u{5}\u{12}\u{A}\u{6}\u{EB}\u{EC}\u{C}" .
            "\u{4}\u{2}\u{2}\u{EC}\u{ED}\u{7}\u{2E}\u{2}\u{2}\u{ED}\u{EF}\u{5}" .
            "\u{12}\u{A}\u{5}\u{EE}\u{E8}\u{3}\u{2}\u{2}\u{2}\u{EE}\u{EB}\u{3}" .
            "\u{2}\u{2}\u{2}\u{EF}\u{F2}\u{3}\u{2}\u{2}\u{2}\u{F0}\u{EE}\u{3}\u{2}" .
            "\u{2}\u{2}\u{F0}\u{F1}\u{3}\u{2}\u{2}\u{2}\u{F1}\u{13}\u{3}\u{2}\u{2}" .
            "\u{2}\u{F2}\u{F0}\u{3}\u{2}\u{2}\u{2}\u{F3}\u{F8}\u{9}\u{4}\u{2}\u{2}" .
            "\u{F4}\u{F5}\u{7}\u{2B}\u{2}\u{2}\u{F5}\u{F8}\u{7}\u{1F}\u{2}\u{2}" .
            "\u{F6}\u{F8}\u{7}\u{3E}\u{2}\u{2}\u{F7}\u{F3}\u{3}\u{2}\u{2}\u{2}" .
            "\u{F7}\u{F4}\u{3}\u{2}\u{2}\u{2}\u{F7}\u{F6}\u{3}\u{2}\u{2}\u{2}\u{F8}" .
            "\u{15}\u{3}\u{2}\u{2}\u{2}\u{F9}\u{FA}\u{7}\u{18}\u{2}\u{2}\u{FA}" .
            "\u{FB}\u{5}\u{1C}\u{F}\u{2}\u{FB}\u{17}\u{3}\u{2}\u{2}\u{2}\u{FC}" .
            "\u{FD}\u{7}\u{D}\u{2}\u{2}\u{FD}\u{102}\u{5}\u{1A}\u{E}\u{2}\u{FE}" .
            "\u{FF}\u{7}\u{3}\u{2}\u{2}\u{FF}\u{101}\u{5}\u{1A}\u{E}\u{2}\u{100}" .
            "\u{FE}\u{3}\u{2}\u{2}\u{2}\u{101}\u{104}\u{3}\u{2}\u{2}\u{2}\u{102}" .
            "\u{100}\u{3}\u{2}\u{2}\u{2}\u{102}\u{103}\u{3}\u{2}\u{2}\u{2}\u{103}" .
            "\u{19}\u{3}\u{2}\u{2}\u{2}\u{104}\u{102}\u{3}\u{2}\u{2}\u{2}\u{105}" .
            "\u{106}\u{7}\u{47}\u{2}\u{2}\u{106}\u{107}\u{7}\u{6}\u{2}\u{2}\u{107}" .
            "\u{108}\u{5}\u{1C}\u{F}\u{2}\u{108}\u{1B}\u{3}\u{2}\u{2}\u{2}\u{109}" .
            "\u{10D}\u{5}\u{22}\u{12}\u{2}\u{10A}\u{10D}\u{5}\u{20}\u{11}\u{2}" .
            "\u{10B}\u{10D}\u{5}\u{1E}\u{10}\u{2}\u{10C}\u{109}\u{3}\u{2}\u{2}" .
            "\u{2}\u{10C}\u{10A}\u{3}\u{2}\u{2}\u{2}\u{10C}\u{10B}\u{3}\u{2}\u{2}" .
            "\u{2}\u{10D}\u{1D}\u{3}\u{2}\u{2}\u{2}\u{10E}\u{10F}\u{5}\u{22}\u{12}" .
            "\u{2}\u{10F}\u{110}\u{7}\u{7}\u{2}\u{2}\u{110}\u{111}\u{5}\u{22}\u{12}" .
            "\u{2}\u{111}\u{1F}\u{3}\u{2}\u{2}\u{2}\u{112}\u{113}\u{7}\u{4}\u{2}" .
            "\u{2}\u{113}\u{118}\u{5}\u{22}\u{12}\u{2}\u{114}\u{115}\u{7}\u{3}" .
            "\u{2}\u{2}\u{115}\u{117}\u{5}\u{22}\u{12}\u{2}\u{116}\u{114}\u{3}" .
            "\u{2}\u{2}\u{2}\u{117}\u{11A}\u{3}\u{2}\u{2}\u{2}\u{118}\u{116}\u{3}" .
            "\u{2}\u{2}\u{2}\u{118}\u{119}\u{3}\u{2}\u{2}\u{2}\u{119}\u{11B}\u{3}" .
            "\u{2}\u{2}\u{2}\u{11A}\u{118}\u{3}\u{2}\u{2}\u{2}\u{11B}\u{11C}\u{7}" .
            "\u{5}\u{2}\u{2}\u{11C}\u{21}\u{3}\u{2}\u{2}\u{2}\u{11D}\u{120}\u{5}" .
            "\u{24}\u{13}\u{2}\u{11E}\u{120}\u{5}\u{26}\u{14}\u{2}\u{11F}\u{11D}" .
            "\u{3}\u{2}\u{2}\u{2}\u{11F}\u{11E}\u{3}\u{2}\u{2}\u{2}\u{120}\u{23}" .
            "\u{3}\u{2}\u{2}\u{2}\u{121}\u{122}\u{7}\u{46}\u{2}\u{2}\u{122}\u{25}" .
            "\u{3}\u{2}\u{2}\u{2}\u{123}\u{128}\u{7}\u{43}\u{2}\u{2}\u{124}\u{128}" .
            "\u{9}\u{5}\u{2}\u{2}\u{125}\u{128}\u{7}\u{45}\u{2}\u{2}\u{126}\u{128}" .
            "\u{7}\u{44}\u{2}\u{2}\u{127}\u{123}\u{3}\u{2}\u{2}\u{2}\u{127}\u{124}" .
            "\u{3}\u{2}\u{2}\u{2}\u{127}\u{125}\u{3}\u{2}\u{2}\u{2}\u{127}\u{126}" .
            "\u{3}\u{2}\u{2}\u{2}\u{128}\u{27}\u{3}\u{2}\u{2}\u{2}\u{129}\u{12A}" .
            "\u{9}\u{6}\u{2}\u{2}\u{12A}\u{29}\u{3}\u{2}\u{2}\u{2}\u{14}\u{2D}" .
            "\u{3F}\u{43}\u{48}\u{4C}\u{50}\u{57}\u{96}\u{99}\u{E6}\u{EE}\u{F0}" .
            "\u{F7}\u{102}\u{10C}\u{118}\u{11F}\u{127}";

        protected static $atn;
        protected static $decisionToDFA;
        protected static $sharedContextCache;

        public function __construct(TokenStream $input)
        {
            parent::__construct($input);

            self::initialize();

            $this->interp = new ParserATNSimulator($this, self::$atn, self::$decisionToDFA, self::$sharedContextCache);
        }

        private static function initialize(): void
        {
            if (self::$atn !== null) {
                return;
            }

            $atn = (new ATNDeserializer())->deserialize(self::SERIALIZED_ATN);

            $decisionToDFA = [];
            for ($i = 0, $count = $atn->getNumberOfDecisions(); $i < $count; ++$i) {
                $decisionToDFA[] = new DFA($atn->getDecisionState($i), $i);
            }

            self::$atn = $atn;
            self::$decisionToDFA = $decisionToDFA;
            self::$sharedContextCache = new PredictionContextCache();
        }

        public function getGrammarFileName(): string
        {
            return 'EZQL.g4';
        }

        public function getRuleNames(): array
        {
            return self::RULE_NAMES;
        }

        public function getSerializedATN(): string
        {
            return self::SERIALIZED_ATN;
        }

        public function getATN(): ATN
        {
            return self::$atn;
        }

        public function getVocabulary(): Vocabulary
        {
            static $vocabulary;

            return $vocabulary = $vocabulary ?? new VocabularyImpl(self::LITERAL_NAMES, self::SYMBOLIC_NAMES);
        }

        /**
         * @throws RecognitionException
         */
        public function stmt(): Context\StmtContext
        {
            $localContext = new Context\StmtContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 0, self::RULE_stmt);

            try {
                $this->setState(43);
                $this->errorHandler->sync($this);

                switch ($this->getInterpreter()->adaptivePredict($this->input, 0, $this->ctx)) {
                    case 1:
                        $this->enterOuterAlt($localContext, 1);
                        $this->setState(40);
                        $this->selectLocation();
                    break;

                    case 2:
                        $this->enterOuterAlt($localContext, 2);
                        $this->setState(41);
                        $this->selectContent();
                    break;

                    case 3:
                        $this->enterOuterAlt($localContext, 3);
                        $this->setState(42);
                        $this->selectContentInfo();
                    break;
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function selectLocation(): Context\SelectLocationContext
        {
            $localContext = new Context\SelectLocationContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 2, self::RULE_selectLocation);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(45);
                $this->match(self::K_SELECT);
                $this->setState(46);
                $this->match(self::K_LOCATION);
                $this->setState(47);
                $localContext->properties = $this->selectProperties();
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function selectContent(): Context\SelectContentContext
        {
            $localContext = new Context\SelectContentContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 4, self::RULE_selectContent);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(49);
                $this->match(self::K_SELECT);
                $this->setState(50);
                $this->match(self::K_CONTENT);
                $this->setState(51);
                $localContext->properties = $this->selectProperties();
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function selectContentInfo(): Context\SelectContentInfoContext
        {
            $localContext = new Context\SelectContentInfoContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 6, self::RULE_selectContentInfo);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(53);
                $this->match(self::K_SELECT);
                $this->setState(54);
                $this->match(self::K_CONTENT);
                $this->setState(55);
                $this->match(self::K_INFO);
                $this->setState(56);
                $localContext->properties = $this->selectProperties();
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function selectProperties(): Context\SelectPropertiesContext
        {
            $localContext = new Context\SelectPropertiesContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 8, self::RULE_selectProperties);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(61);
                $this->errorHandler->sync($this);
                $_la = $this->input->LA(1);

                if ($_la === self::K_FILTER) {
                    $this->setState(58);
                    $this->match(self::K_FILTER);
                    $this->setState(59);
                    $this->match(self::K_BY);
                    $this->setState(60);
                    $localContext->filter = $this->recursiveExpr(0);
                }
                $this->setState(65);
                $this->errorHandler->sync($this);
                $_la = $this->input->LA(1);

                if ($_la === self::K_QUERY) {
                    $this->setState(63);
                    $this->match(self::K_QUERY);
                    $this->setState(64);
                    $localContext->query = $this->recursiveExpr(0);
                }
                $this->setState(70);
                $this->errorHandler->sync($this);
                $_la = $this->input->LA(1);

                if ($_la === self::K_ORDER) {
                    $this->setState(67);
                    $this->match(self::K_ORDER);
                    $this->setState(68);
                    $this->match(self::K_BY);
                    $this->setState(69);
                    $localContext->sortClauses = $this->sortClauseList();
                }
                $this->setState(74);
                $this->errorHandler->sync($this);
                $_la = $this->input->LA(1);

                if ($_la === self::K_LIMIT) {
                    $this->setState(72);
                    $this->match(self::K_LIMIT);
                    $this->setState(73);
                    $localContext->limit = $this->argument();
                }
                $this->setState(78);
                $this->errorHandler->sync($this);
                $_la = $this->input->LA(1);

                if ($_la === self::K_OFFSET) {
                    $this->setState(76);
                    $this->match(self::K_OFFSET);
                    $this->setState(77);
                    $localContext->offset = $this->argument();
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function sortClauseList(): Context\SortClauseListContext
        {
            $localContext = new Context\SortClauseListContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 10, self::RULE_sortClauseList);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(80);
                $this->sortClause();
                $this->setState(85);
                $this->errorHandler->sync($this);

                $_la = $this->input->LA(1);
                while ($_la === self::T__0) {
                    $this->setState(81);
                    $this->match(self::T__0);
                    $this->setState(82);
                    $this->sortClause();
                    $this->setState(87);
                    $this->errorHandler->sync($this);
                    $_la = $this->input->LA(1);
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function sortClause(): Context\SortClauseContext
        {
            $localContext = new Context\SortClauseContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 12, self::RULE_sortClause);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(88);
                $localContext->name = $this->match(self::ID);
                $this->setState(89);
                $this->sortOrder();
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function sortOrder(): Context\SortOrderContext
        {
            $localContext = new Context\SortOrderContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 14, self::RULE_sortOrder);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(91);

                $localContext->order = $this->input->LT(1);
                $_la = $this->input->LA(1);

                if (!($_la === self::K_ASC || $_la === self::K_DESC)) {
                    $localContext->order = $this->errorHandler->recoverInline($this);
                } else {
                    if ($this->input->LA(1) === Token::EOF) {
                        $this->matchedEOF = true;
                    }

                    $this->errorHandler->reportMatch($this);
                    $this->consume();
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function expr(): Context\ExprContext
        {
            return $this->recursiveExpr(0);
        }

        /**
         * @throws RecognitionException
         */
        private function recursiveExpr(int $precedence): Context\ExprContext
        {
            $parentContext = $this->ctx;
            $parentState = $this->getState();
            $localContext = new Context\ExprContext($this->ctx, $parentState);
            $previousContext = $localContext;
            $startState = 16;
            $this->enterRecursionRule($localContext, 16, self::RULE_expr, $precedence);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(228);
                $this->errorHandler->sync($this);

                switch ($this->getInterpreter()->adaptivePredict($this->input, 9, $this->ctx)) {
                    case 1:
                        $localContext = new Context\MatchAllExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;

                        $this->setState(94);
                        $this->match(self::K_MATCH);
                        $this->setState(95);
                        $this->match(self::K_ALL);
                    break;

                    case 2:
                        $localContext = new Context\MatchNoneExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(96);
                        $this->match(self::K_MATCH);
                        $this->setState(97);
                        $this->match(self::K_NONE);
                    break;

                    case 3:
                        $localContext = new Context\IsMainLocationExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(98);
                        $this->match(self::K_IS);
                        $this->setState(99);
                        $this->match(self::K_MAIN);
                        $this->setState(100);
                        $this->match(self::K_LOCATION);
                    break;

                    case 4:
                        $localContext = new Context\IsNotMainLocationExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(101);
                        $this->match(self::K_IS);
                        $this->setState(102);
                        $this->match(self::K_NOT);
                        $this->setState(103);
                        $this->match(self::K_MAIN);
                        $this->setState(104);
                        $this->match(self::K_LOCATION);
                    break;

                    case 5:
                        $localContext = new Context\VisibilityExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(105);
                        $this->match(self::K_IS);
                        $this->setState(106);

                        $localContext->flag = $this->input->LT(1);
                        $_la = $this->input->LA(1);

                        if (!($_la === self::K_HIDDEN || $_la === self::K_VISIBLE)) {
                            $localContext->flag = $this->errorHandler->recoverInline($this);
                        } else {
                            if ($this->input->LA(1) === Token::EOF) {
                                $this->matchedEOF = true;
                            }

                            $this->errorHandler->reportMatch($this);
                            $this->consume();
                        }
                    break;

                    case 6:
                        $localContext = new Context\IsFieldEmptyExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(107);
                        $this->match(self::K_FIELD);
                        $this->setState(108);
                        $localContext->field = $this->match(self::ID);
                        $this->setState(109);
                        $this->match(self::K_IS);
                        $this->setState(110);
                        $this->match(self::K_EMPTY);
                    break;

                    case 7:
                        $localContext = new Context\IsNotFieldEmptyExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(111);
                        $this->match(self::K_FIELD);
                        $this->setState(112);
                        $localContext->field = $this->match(self::ID);
                        $this->setState(113);
                        $this->match(self::K_IS);
                        $this->setState(114);
                        $this->match(self::K_NOT);
                        $this->setState(115);
                        $this->match(self::K_EMPTY);
                    break;

                    case 8:
                        $localContext = new Context\FieldExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(116);
                        $this->match(self::K_FIELD);
                        $this->setState(117);
                        $localContext->field = $this->match(self::ID);
                        $this->setState(118);
                        $localContext->op = $this->operator();
                        $this->setState(119);
                        $localContext->val = $this->value();
                    break;

                    case 9:
                        $localContext = new Context\FieldRelationExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(121);
                        $this->match(self::K_FIELD);
                        $this->setState(122);
                        $this->match(self::K_RELATION);
                        $this->setState(123);
                        $localContext->field = $this->match(self::ID);
                        $this->setState(124);
                        $localContext->op = $this->operator();
                        $this->setState(125);
                        $localContext->val = $this->value();
                    break;

                    case 10:
                        $localContext = new Context\LocationPriorityExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(127);
                        $this->match(self::K_LOCATION);
                        $this->setState(128);
                        $this->match(self::K_PRIORITY);
                        $this->setState(129);
                        $localContext->op = $this->operator();
                        $this->setState(130);
                        $localContext->val = $this->value();
                    break;

                    case 11:
                        $localContext = new Context\LocationDepthExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(132);
                        $this->match(self::K_LOCATION);
                        $this->setState(133);
                        $this->match(self::K_DEPTH);
                        $this->setState(134);
                        $localContext->op = $this->operator();
                        $this->setState(135);
                        $localContext->val = $this->value();
                    break;

                    case 12:
                        $localContext = new Context\CreatedExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(137);
                        $this->match(self::K_CREATED);
                        $this->setState(138);
                        $localContext->op = $this->operator();
                        $this->setState(139);
                        $localContext->val = $this->value();
                    break;

                    case 13:
                        $localContext = new Context\ModifiedExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(141);
                        $this->match(self::K_MODIFIED);
                        $this->setState(142);
                        $localContext->op = $this->operator();
                        $this->setState(143);
                        $localContext->val = $this->value();
                    break;

                    case 14:
                        $localContext = new Context\FulltextExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(145);
                        $this->match(self::K_FULLTEXT);
                        $this->setState(146);
                        $localContext->val = $this->value();
                        $this->setState(148);
                        $this->errorHandler->sync($this);

                        switch ($this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx)) {
                            case 1:
                                $this->setState(147);
                                $this->fuzziness();
                            break;
                        }
                        $this->setState(151);
                        $this->errorHandler->sync($this);

                        switch ($this->getInterpreter()->adaptivePredict($this->input, 8, $this->ctx)) {
                            case 1:
                                $this->setState(150);
                                $this->boosting();
                            break;
                        }
                    break;

                    case 15:
                        $localContext = new Context\UserMetadataExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(153);
                        $localContext->target = $this->userMetadataTarget();
                        $this->setState(154);
                        $localContext->op = $this->operator();
                        $this->setState(155);
                        $localContext->val = $this->value();
                    break;

                    case 16:
                        $localContext = new Context\AncestorExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(157);
                        $this->match(self::K_ANCESTOR);
                        $this->setState(158);
                        $localContext->op = $this->operator();
                        $this->setState(159);
                        $localContext->val = $this->value();
                    break;

                    case 17:
                        $localContext = new Context\ContentIdExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(161);
                        $this->match(self::K_CONTENT);
                        $this->setState(162);
                        $this->match(self::K_ID);
                        $this->setState(163);
                        $localContext->op = $this->operator();
                        $this->setState(164);
                        $localContext->val = $this->value();
                    break;

                    case 18:
                        $localContext = new Context\ContentTypeIdExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(166);
                        $this->match(self::K_CONTENT);
                        $this->setState(167);
                        $this->match(self::K_TYPE);
                        $this->setState(168);
                        $this->match(self::K_ID);
                        $this->setState(169);
                        $localContext->op = $this->operator();
                        $this->setState(170);
                        $localContext->val = $this->value();
                    break;

                    case 19:
                        $localContext = new Context\ContentTypeIdentifierExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(172);
                        $this->match(self::K_CONTENT);
                        $this->setState(173);
                        $this->match(self::K_TYPE);
                        $this->setState(174);
                        $this->match(self::K_IDENTIFIER);
                        $this->setState(175);
                        $localContext->op = $this->operator();
                        $this->setState(176);
                        $localContext->val = $this->value();
                    break;

                    case 20:
                        $localContext = new Context\LanguageCodeExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(178);
                        $this->match(self::K_LANGUAGE);
                        $this->setState(179);
                        $this->match(self::K_CODE);
                        $this->setState(180);
                        $localContext->op = $this->operator();
                        $this->setState(181);
                        $localContext->val = $this->value();
                    break;

                    case 21:
                        $localContext = new Context\LocationIdExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(183);
                        $this->match(self::K_LOCATION);
                        $this->setState(184);
                        $this->match(self::K_ID);
                        $this->setState(185);
                        $localContext->op = $this->operator();
                        $this->setState(186);
                        $localContext->val = $this->value();
                    break;

                    case 22:
                        $localContext = new Context\LocationRemoteIdExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(188);
                        $this->match(self::K_LOCATION);
                        $this->setState(189);
                        $this->match(self::K_REMOTE);
                        $this->setState(190);
                        $this->match(self::K_ID);
                        $this->setState(191);
                        $localContext->op = $this->operator();
                        $this->setState(192);
                        $localContext->val = $this->value();
                    break;

                    case 23:
                        $localContext = new Context\ObjectStateIdExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(194);
                        $this->match(self::K_OBJECT);
                        $this->setState(195);
                        $this->match(self::K_STATE);
                        $this->setState(196);
                        $this->match(self::K_ID);
                        $this->setState(197);
                        $localContext->op = $this->operator();
                        $this->setState(198);
                        $localContext->val = $this->value();
                    break;

                    case 24:
                        $localContext = new Context\ParentLocationIdExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(200);
                        $this->match(self::K_PARENT);
                        $this->setState(201);
                        $this->match(self::K_LOCATION);
                        $this->setState(202);
                        $this->match(self::K_ID);
                        $this->setState(203);
                        $localContext->op = $this->operator();
                        $this->setState(204);
                        $localContext->val = $this->value();
                    break;

                    case 25:
                        $localContext = new Context\RemoteIdExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(206);
                        $this->match(self::K_REMOTE);
                        $this->setState(207);
                        $this->match(self::K_ID);
                        $this->setState(208);
                        $localContext->op = $this->operator();
                        $this->setState(209);
                        $localContext->val = $this->value();
                    break;

                    case 26:
                        $localContext = new Context\SectionIdExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(211);
                        $this->match(self::K_SECTION);
                        $this->setState(212);
                        $this->match(self::K_ID);
                        $this->setState(213);
                        $localContext->op = $this->operator();
                        $this->setState(214);
                        $localContext->val = $this->value();
                    break;

                    case 27:
                        $localContext = new Context\SubtreeExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(216);
                        $this->match(self::K_SUBTREE);
                        $this->setState(217);
                        $localContext->op = $this->operator();
                        $this->setState(218);
                        $localContext->val = $this->value();
                    break;

                    case 28:
                        $localContext = new Context\CriterionExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(220);
                        $localContext->clazz = $this->match(self::ID);
                        $this->setState(221);
                        $localContext->op = $this->operator();
                        $this->setState(222);
                        $localContext->val = $this->value();
                    break;

                    case 29:
                        $localContext = new Context\InnerExprContext($localContext);
                        $this->ctx = $localContext;
                        $previousContext = $localContext;
                        $this->setState(224);
                        $this->match(self::T__1);
                        $this->setState(225);
                        $this->recursiveExpr(0);
                        $this->setState(226);
                        $this->match(self::T__2);
                    break;
                }
                $this->ctx->stop = $this->input->LT(-1);
                $this->setState(238);
                $this->errorHandler->sync($this);

                $alt = $this->getInterpreter()->adaptivePredict($this->input, 11, $this->ctx);

                while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
                    if ($alt === 1) {
                        if ($this->getParseListeners() !== null) {
                            $this->triggerExitRuleEvent();
                        }

                        $previousContext = $localContext;
                        $this->setState(236);
                        $this->errorHandler->sync($this);

                        switch ($this->getInterpreter()->adaptivePredict($this->input, 10, $this->ctx)) {
                            case 1:
                                $localContext = new Context\AndExprContext(new Context\ExprContext($parentContext, $parentState));
                                $localContext->left = $previousContext;

                                $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
                                $this->setState(230);

                                if (!($this->precpred($this->ctx, 3))) {
                                    throw new FailedPredicateException($this, '\\$this->precpred(\\$this->ctx, 3)');
                                }
                                $this->setState(231);
                                $this->match(self::K_AND);
                                $this->setState(232);
                                $localContext->right = $this->recursiveExpr(4);
                            break;

                            case 2:
                                $localContext = new Context\OrExprContext(new Context\ExprContext($parentContext, $parentState));
                                $localContext->left = $previousContext;

                                $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
                                $this->setState(233);

                                if (!($this->precpred($this->ctx, 2))) {
                                    throw new FailedPredicateException($this, '\\$this->precpred(\\$this->ctx, 2)');
                                }
                                $this->setState(234);
                                $this->match(self::K_OR);
                                $this->setState(235);
                                $localContext->right = $this->recursiveExpr(3);
                            break;
                        }
                    }

                    $this->setState(240);
                    $this->errorHandler->sync($this);

                    $alt = $this->getInterpreter()->adaptivePredict($this->input, 11, $this->ctx);
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->unrollRecursionContexts($parentContext);
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function operator(): Context\OperatorContext
        {
            $localContext = new Context\OperatorContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 18, self::RULE_operator);

            try {
                $this->setState(245);
                $this->errorHandler->sync($this);

                switch ($this->input->LA(1)) {
                    case self::K_BETWEEN:
                    case self::K_CONTAINS:
                    case self::K_IN:
                    case self::K_LIKE:
                    case self::EQ:
                    case self::GT:
                    case self::GTE:
                    case self::LT:
                    case self::LTE:
                        $localContext = new Context\BuildInOperatorContext($localContext);
                        $this->enterOuterAlt($localContext, 1);
                        $this->setState(241);

                        $localContext->op = $this->input->LT(1);
                        $_la = $this->input->LA(1);

                        if (!((((($_la - 10)) & ~0x3f) === 0 && ((1 << ($_la - 10)) & ((1 << (self::K_BETWEEN - 10)) | (1 << (self::K_CONTAINS - 10)) | (1 << (self::K_IN - 10)) | (1 << (self::K_LIKE - 10)) | (1 << (self::EQ - 10)) | (1 << (self::GT - 10)) | (1 << (self::GTE - 10)) | (1 << (self::LT - 10)) | (1 << (self::LTE - 10)))) !== 0))) {
                            $localContext->op = $this->errorHandler->recoverInline($this);
                        } else {
                            if ($this->input->LA(1) === Token::EOF) {
                                $this->matchedEOF = true;
                            }

                            $this->errorHandler->reportMatch($this);
                            $this->consume();
                        }
                        break;

                    case self::K_NOT:
                        $localContext = new Context\NotInOperatorContext($localContext);
                        $this->enterOuterAlt($localContext, 2);
                        $this->setState(242);
                        $this->match(self::K_NOT);
                        $this->setState(243);
                        $this->match(self::K_IN);
                        break;

                    case self::NEQ:
                        $localContext = new Context\NotEQContext($localContext);
                        $this->enterOuterAlt($localContext, 3);
                        $this->setState(244);
                        $this->match(self::NEQ);
                        break;

                default:
                    throw new NoViableAltException($this);
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function fuzziness(): Context\FuzzinessContext
        {
            $localContext = new Context\FuzzinessContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 20, self::RULE_fuzziness);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(247);
                $this->match(self::K_FUZZINESS);
                $this->setState(248);
                $localContext->val = $this->value();
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function boosting(): Context\BoostingContext
        {
            $localContext = new Context\BoostingContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 22, self::RULE_boosting);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(250);
                $this->match(self::K_BOOST);
                $this->setState(251);
                $this->fieldBoost();
                $this->setState(256);
                $this->errorHandler->sync($this);

                $alt = $this->getInterpreter()->adaptivePredict($this->input, 13, $this->ctx);

                while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
                    if ($alt === 1) {
                        $this->setState(252);
                        $this->match(self::T__0);
                        $this->setState(253);
                        $this->fieldBoost();
                    }

                    $this->setState(258);
                    $this->errorHandler->sync($this);

                    $alt = $this->getInterpreter()->adaptivePredict($this->input, 13, $this->ctx);
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function fieldBoost(): Context\FieldBoostContext
        {
            $localContext = new Context\FieldBoostContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 24, self::RULE_fieldBoost);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(259);
                $localContext->field = $this->match(self::ID);
                $this->setState(260);
                $this->match(self::T__3);
                $this->setState(261);
                $localContext->val = $this->value();
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function value(): Context\ValueContext
        {
            $localContext = new Context\ValueContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 26, self::RULE_value);

            try {
                $this->setState(266);
                $this->errorHandler->sync($this);

                switch ($this->getInterpreter()->adaptivePredict($this->input, 14, $this->ctx)) {
                    case 1:
                        $this->enterOuterAlt($localContext, 1);
                        $this->setState(263);
                        $this->argument();
                    break;

                    case 2:
                        $this->enterOuterAlt($localContext, 2);
                        $this->setState(264);
                        $this->argumentList();
                    break;

                    case 3:
                        $this->enterOuterAlt($localContext, 3);
                        $this->setState(265);
                        $this->argumentRange();
                    break;
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function argumentRange(): Context\ArgumentRangeContext
        {
            $localContext = new Context\ArgumentRangeContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 28, self::RULE_argumentRange);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(268);
                $localContext->a = $this->argument();
                $this->setState(269);
                $this->match(self::T__4);
                $this->setState(270);
                $localContext->b = $this->argument();
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function argumentList(): Context\ArgumentListContext
        {
            $localContext = new Context\ArgumentListContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 30, self::RULE_argumentList);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(272);
                $this->match(self::T__1);
                $this->setState(273);
                $this->argument();
                $this->setState(278);
                $this->errorHandler->sync($this);

                $_la = $this->input->LA(1);
                while ($_la === self::T__0) {
                    $this->setState(274);
                    $this->match(self::T__0);
                    $this->setState(275);
                    $this->argument();
                    $this->setState(280);
                    $this->errorHandler->sync($this);
                    $_la = $this->input->LA(1);
                }
                $this->setState(281);
                $this->match(self::T__2);
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function argument(): Context\ArgumentContext
        {
            $localContext = new Context\ArgumentContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 32, self::RULE_argument);

            try {
                $this->setState(285);
                $this->errorHandler->sync($this);

                switch ($this->input->LA(1)) {
                    case self::PARAMETER_NAME:
                        $this->enterOuterAlt($localContext, 1);
                        $this->setState(283);
                        $this->parameter();
                        break;

                    case self::K_FALSE:
                    case self::K_TRUE:
                    case self::INT:
                    case self::DOUBLE:
                    case self::STRING:
                        $this->enterOuterAlt($localContext, 2);
                        $this->setState(284);
                        $this->scalar();
                        break;

                default:
                    throw new NoViableAltException($this);
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function parameter(): Context\ParameterContext
        {
            $localContext = new Context\ParameterContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 34, self::RULE_parameter);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(287);
                $localContext->name = $this->match(self::PARAMETER_NAME);
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function scalar(): Context\ScalarContext
        {
            $localContext = new Context\ScalarContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 36, self::RULE_scalar);

            try {
                $this->setState(293);
                $this->errorHandler->sync($this);

                switch ($this->input->LA(1)) {
                    case self::INT:
                        $localContext = new Context\IntContext($localContext);
                        $this->enterOuterAlt($localContext, 1);
                        $this->setState(289);
                        $localContext->val = $this->match(self::INT);
                        break;

                    case self::K_FALSE:
                    case self::K_TRUE:
                        $localContext = new Context\BooleanContext($localContext);
                        $this->enterOuterAlt($localContext, 2);
                        $this->setState(290);

                        $localContext->val = $this->input->LT(1);
                        $_la = $this->input->LA(1);

                        if (!($_la === self::K_FALSE || $_la === self::K_TRUE)) {
                            $localContext->val = $this->errorHandler->recoverInline($this);
                        } else {
                            if ($this->input->LA(1) === Token::EOF) {
                                $this->matchedEOF = true;
                            }

                            $this->errorHandler->reportMatch($this);
                            $this->consume();
                        }
                        break;

                    case self::STRING:
                        $localContext = new Context\StringContext($localContext);
                        $this->enterOuterAlt($localContext, 3);
                        $this->setState(291);
                        $localContext->val = $this->match(self::STRING);
                        break;

                    case self::DOUBLE:
                        $localContext = new Context\DoubleContext($localContext);
                        $this->enterOuterAlt($localContext, 4);
                        $this->setState(292);
                        $localContext->val = $this->match(self::DOUBLE);
                        break;

                default:
                    throw new NoViableAltException($this);
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        /**
         * @throws RecognitionException
         */
        public function userMetadataTarget(): Context\UserMetadataTargetContext
        {
            $localContext = new Context\UserMetadataTargetContext($this->ctx, $this->getState());

            $this->enterRule($localContext, 38, self::RULE_userMetadataTarget);

            try {
                $this->enterOuterAlt($localContext, 1);
                $this->setState(295);

                $localContext->target = $this->input->LT(1);
                $_la = $this->input->LA(1);

                if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & ((1 << self::K_GROUP) | (1 << self::K_MODIFIER) | (1 << self::K_OWNER))) !== 0))) {
                    $localContext->target = $this->errorHandler->recoverInline($this);
                } else {
                    if ($this->input->LA(1) === Token::EOF) {
                        $this->matchedEOF = true;
                    }

                    $this->errorHandler->reportMatch($this);
                    $this->consume();
                }
            } catch (RecognitionException $exception) {
                $localContext->exception = $exception;
                $this->errorHandler->reportError($this, $exception);
                $this->errorHandler->recover($this, $exception);
            } finally {
                $this->exitRule();
            }

            return $localContext;
        }

        public function sempred(?RuleContext $localContext, int $ruleIndex, int $predicateIndex): bool
        {
            switch ($ruleIndex) {
                    case 8:
                        return $this->sempredExpr($localContext, $predicateIndex);

                default:
                    return true;
                }
        }

        private function sempredExpr(?Context\ExprContext $localContext, int $predicateIndex): bool
        {
            switch ($predicateIndex) {
                case 0:
                    return $this->precpred($this->ctx, 3);

                case 1:
                    return $this->precpred($this->ctx, 2);
            }

            return true;
        }
    }
}

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\Context {
    use Antlr\Antlr4\Runtime\ParserRuleContext;
    use Antlr\Antlr4\Runtime\Token;
    use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;
    use Antlr\Antlr4\Runtime\Tree\TerminalNode;
    use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLParser;
    use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLVisitor;

    class StmtContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_stmt;
        }

        public function selectLocation(): ?SelectLocationContext
        {
            return $this->getTypedRuleContext(SelectLocationContext::class, 0);
        }

        public function selectContent(): ?SelectContentContext
        {
            return $this->getTypedRuleContext(SelectContentContext::class, 0);
        }

        public function selectContentInfo(): ?SelectContentInfoContext
        {
            return $this->getTypedRuleContext(SelectContentInfoContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitStmt($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SelectLocationContext extends ParserRuleContext
    {
        /**
         * @var SelectPropertiesContext|null
         */
        public $properties;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_selectLocation;
        }

        public function K_SELECT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_SELECT, 0);
        }

        public function K_LOCATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LOCATION, 0);
        }

        public function selectProperties(): ?SelectPropertiesContext
        {
            return $this->getTypedRuleContext(SelectPropertiesContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSelectLocation($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SelectContentContext extends ParserRuleContext
    {
        /**
         * @var SelectPropertiesContext|null
         */
        public $properties;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_selectContent;
        }

        public function K_SELECT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_SELECT, 0);
        }

        public function K_CONTENT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_CONTENT, 0);
        }

        public function selectProperties(): ?SelectPropertiesContext
        {
            return $this->getTypedRuleContext(SelectPropertiesContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSelectContent($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SelectContentInfoContext extends ParserRuleContext
    {
        /**
         * @var SelectPropertiesContext|null
         */
        public $properties;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_selectContentInfo;
        }

        public function K_SELECT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_SELECT, 0);
        }

        public function K_CONTENT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_CONTENT, 0);
        }

        public function K_INFO(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_INFO, 0);
        }

        public function selectProperties(): ?SelectPropertiesContext
        {
            return $this->getTypedRuleContext(SelectPropertiesContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSelectContentInfo($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SelectPropertiesContext extends ParserRuleContext
    {
        /**
         * @var ExprContext|null
         */
        public $filter;

        /**
         * @var ExprContext|null
         */
        public $query;

        /**
         * @var SortClauseListContext|null
         */
        public $sortClauses;

        /**
         * @var ArgumentContext|null
         */
        public $limit;

        /**
         * @var ArgumentContext|null
         */
        public $offset;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_selectProperties;
        }

        public function K_FILTER(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_FILTER, 0);
        }

        /**
         * @return array<TerminalNode>|TerminalNode|null
         */
        public function K_BY(?int $index = null)
        {
            if ($index === null) {
                return $this->getTokens(EZQLParser::K_BY);
            }

            return $this->getToken(EZQLParser::K_BY, $index);
        }

        public function K_QUERY(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_QUERY, 0);
        }

        public function K_ORDER(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ORDER, 0);
        }

        public function K_LIMIT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LIMIT, 0);
        }

        public function K_OFFSET(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_OFFSET, 0);
        }

        /**
         * @return array<ExprContext>|ExprContext|null
         */
        public function expr(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(ExprContext::class);
            }

            return $this->getTypedRuleContext(ExprContext::class, $index);
        }

        public function sortClauseList(): ?SortClauseListContext
        {
            return $this->getTypedRuleContext(SortClauseListContext::class, 0);
        }

        /**
         * @return array<ArgumentContext>|ArgumentContext|null
         */
        public function argument(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(ArgumentContext::class);
            }

            return $this->getTypedRuleContext(ArgumentContext::class, $index);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSelectProperties($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SortClauseListContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_sortClauseList;
        }

        /**
         * @return array<SortClauseContext>|SortClauseContext|null
         */
        public function sortClause(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(SortClauseContext::class);
            }

            return $this->getTypedRuleContext(SortClauseContext::class, $index);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSortClauseList($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SortClauseContext extends ParserRuleContext
    {
        /**
         * @var Token|null
         */
        public $name;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_sortClause;
        }

        public function sortOrder(): ?SortOrderContext
        {
            return $this->getTypedRuleContext(SortOrderContext::class, 0);
        }

        public function ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::ID, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSortClause($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SortOrderContext extends ParserRuleContext
    {
        /**
         * @var Token|null
         */
        public $order;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_sortOrder;
        }

        public function K_ASC(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ASC, 0);
        }

        public function K_DESC(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_DESC, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSortOrder($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ExprContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_expr;
        }

        public function copyFrom(ParserRuleContext $context): void
        {
            parent::copyFrom($context);
        }
    }

    class IsFieldEmptyExprContext extends ExprContext
    {
        /**
         * @var Token|null
         */
        public $field;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_FIELD(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_FIELD, 0);
        }

        public function K_IS(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_IS, 0);
        }

        public function K_EMPTY(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_EMPTY, 0);
        }

        public function ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::ID, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitIsFieldEmptyExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class FieldRelationExprContext extends ExprContext
    {
        /**
         * @var Token|null
         */
        public $field;

        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_FIELD(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_FIELD, 0);
        }

        public function K_RELATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_RELATION, 0);
        }

        public function ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitFieldRelationExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class LocationRemoteIdExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_LOCATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LOCATION, 0);
        }

        public function K_REMOTE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_REMOTE, 0);
        }

        public function K_ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitLocationRemoteIdExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class VisibilityExprContext extends ExprContext
    {
        /**
         * @var Token|null
         */
        public $flag;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_IS(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_IS, 0);
        }

        public function K_VISIBLE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_VISIBLE, 0);
        }

        public function K_HIDDEN(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_HIDDEN, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitVisibilityExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ContentIdExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_CONTENT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_CONTENT, 0);
        }

        public function K_ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitContentIdExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class RemoteIdExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_REMOTE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_REMOTE, 0);
        }

        public function K_ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitRemoteIdExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ObjectStateIdExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_OBJECT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_OBJECT, 0);
        }

        public function K_STATE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_STATE, 0);
        }

        public function K_ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitObjectStateIdExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class LanguageCodeExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_LANGUAGE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LANGUAGE, 0);
        }

        public function K_CODE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_CODE, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitLanguageCodeExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class IsMainLocationExprContext extends ExprContext
    {
        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_IS(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_IS, 0);
        }

        public function K_MAIN(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_MAIN, 0);
        }

        public function K_LOCATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LOCATION, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitIsMainLocationExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class LocationPriorityExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_LOCATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LOCATION, 0);
        }

        public function K_PRIORITY(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_PRIORITY, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitLocationPriorityExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ContentTypeIdentifierExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_CONTENT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_CONTENT, 0);
        }

        public function K_TYPE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_TYPE, 0);
        }

        public function K_IDENTIFIER(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_IDENTIFIER, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitContentTypeIdentifierExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class LocationIdExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_LOCATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LOCATION, 0);
        }

        public function K_ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitLocationIdExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class FulltextExprContext extends ExprContext
    {
        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_FULLTEXT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_FULLTEXT, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function fuzziness(): ?FuzzinessContext
        {
            return $this->getTypedRuleContext(FuzzinessContext::class, 0);
        }

        public function boosting(): ?BoostingContext
        {
            return $this->getTypedRuleContext(BoostingContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitFulltextExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ParentLocationIdExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_PARENT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_PARENT, 0);
        }

        public function K_LOCATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LOCATION, 0);
        }

        public function K_ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitParentLocationIdExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class CriterionExprContext extends ExprContext
    {
        /**
         * @var Token|null
         */
        public $clazz;

        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitCriterionExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class UserMetadataExprContext extends ExprContext
    {
        /**
         * @var UserMetadataTargetContext|null
         */
        public $target;

        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function userMetadataTarget(): ?UserMetadataTargetContext
        {
            return $this->getTypedRuleContext(UserMetadataTargetContext::class, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitUserMetadataExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class MatchAllExprContext extends ExprContext
    {
        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_MATCH(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_MATCH, 0);
        }

        public function K_ALL(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ALL, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitMatchAllExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class LocationDepthExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_LOCATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LOCATION, 0);
        }

        public function K_DEPTH(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_DEPTH, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitLocationDepthExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class OrExprContext extends ExprContext
    {
        /**
         * @var ExprContext|null
         */
        public $left;

        /**
         * @var ExprContext|null
         */
        public $right;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_OR(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_OR, 0);
        }

        /**
         * @return array<ExprContext>|ExprContext|null
         */
        public function expr(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(ExprContext::class);
            }

            return $this->getTypedRuleContext(ExprContext::class, $index);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitOrExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class IsNotMainLocationExprContext extends ExprContext
    {
        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_IS(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_IS, 0);
        }

        public function K_NOT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_NOT, 0);
        }

        public function K_MAIN(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_MAIN, 0);
        }

        public function K_LOCATION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LOCATION, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitIsNotMainLocationExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class InnerExprContext extends ExprContext
    {
        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function expr(): ?ExprContext
        {
            return $this->getTypedRuleContext(ExprContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitInnerExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SubtreeExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_SUBTREE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_SUBTREE, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSubtreeExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class FieldExprContext extends ExprContext
    {
        /**
         * @var Token|null
         */
        public $field;

        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_FIELD(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_FIELD, 0);
        }

        public function ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitFieldExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ModifiedExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_MODIFIED(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_MODIFIED, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitModifiedExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class AncestorExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_ANCESTOR(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ANCESTOR, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitAncestorExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class MatchNoneExprContext extends ExprContext
    {
        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_MATCH(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_MATCH, 0);
        }

        public function K_NONE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_NONE, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitMatchNoneExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class IsNotFieldEmptyExprContext extends ExprContext
    {
        /**
         * @var Token|null
         */
        public $field;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_FIELD(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_FIELD, 0);
        }

        public function K_IS(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_IS, 0);
        }

        public function K_NOT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_NOT, 0);
        }

        public function K_EMPTY(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_EMPTY, 0);
        }

        public function ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::ID, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitIsNotFieldEmptyExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class SectionIdExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_SECTION(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_SECTION, 0);
        }

        public function K_ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitSectionIdExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class CreatedExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_CREATED(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_CREATED, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitCreatedExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ContentTypeIdExprContext extends ExprContext
    {
        /**
         * @var OperatorContext|null
         */
        public $op;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_CONTENT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_CONTENT, 0);
        }

        public function K_TYPE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_TYPE, 0);
        }

        public function K_ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_ID, 0);
        }

        public function operator(): ?OperatorContext
        {
            return $this->getTypedRuleContext(OperatorContext::class, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitContentTypeIdExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class AndExprContext extends ExprContext
    {
        /**
         * @var ExprContext|null
         */
        public $left;

        /**
         * @var ExprContext|null
         */
        public $right;

        public function __construct(ExprContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_AND(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_AND, 0);
        }

        /**
         * @return array<ExprContext>|ExprContext|null
         */
        public function expr(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(ExprContext::class);
            }

            return $this->getTypedRuleContext(ExprContext::class, $index);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitAndExpr($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class OperatorContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_operator;
        }

        public function copyFrom(ParserRuleContext $context): void
        {
            parent::copyFrom($context);
        }
    }

    class BuildInOperatorContext extends OperatorContext
    {
        /**
         * @var Token|null
         */
        public $op;

        public function __construct(OperatorContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function EQ(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::EQ, 0);
        }

        public function K_IN(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_IN, 0);
        }

        public function GT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::GT, 0);
        }

        public function LT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::LT, 0);
        }

        public function GTE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::GTE, 0);
        }

        public function LTE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::LTE, 0);
        }

        public function K_LIKE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_LIKE, 0);
        }

        public function K_BETWEEN(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_BETWEEN, 0);
        }

        public function K_CONTAINS(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_CONTAINS, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitBuildInOperator($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class NotEQContext extends OperatorContext
    {
        public function __construct(OperatorContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function NEQ(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::NEQ, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitNotEQ($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class NotInOperatorContext extends OperatorContext
    {
        public function __construct(OperatorContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_NOT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_NOT, 0);
        }

        public function K_IN(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_IN, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitNotInOperator($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class FuzzinessContext extends ParserRuleContext
    {
        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_fuzziness;
        }

        public function K_FUZZINESS(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_FUZZINESS, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitFuzziness($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class BoostingContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_boosting;
        }

        public function K_BOOST(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_BOOST, 0);
        }

        /**
         * @return array<FieldBoostContext>|FieldBoostContext|null
         */
        public function fieldBoost(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(FieldBoostContext::class);
            }

            return $this->getTypedRuleContext(FieldBoostContext::class, $index);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitBoosting($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class FieldBoostContext extends ParserRuleContext
    {
        /**
         * @var Token|null
         */
        public $field;

        /**
         * @var ValueContext|null
         */
        public $val;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_fieldBoost;
        }

        public function ID(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::ID, 0);
        }

        public function value(): ?ValueContext
        {
            return $this->getTypedRuleContext(ValueContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitFieldBoost($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ValueContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_value;
        }

        public function argument(): ?ArgumentContext
        {
            return $this->getTypedRuleContext(ArgumentContext::class, 0);
        }

        public function argumentList(): ?ArgumentListContext
        {
            return $this->getTypedRuleContext(ArgumentListContext::class, 0);
        }

        public function argumentRange(): ?ArgumentRangeContext
        {
            return $this->getTypedRuleContext(ArgumentRangeContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitValue($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ArgumentRangeContext extends ParserRuleContext
    {
        /**
         * @var ArgumentContext|null
         */
        public $a;

        /**
         * @var ArgumentContext|null
         */
        public $b;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_argumentRange;
        }

        /**
         * @return array<ArgumentContext>|ArgumentContext|null
         */
        public function argument(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(ArgumentContext::class);
            }

            return $this->getTypedRuleContext(ArgumentContext::class, $index);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitArgumentRange($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ArgumentListContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_argumentList;
        }

        /**
         * @return array<ArgumentContext>|ArgumentContext|null
         */
        public function argument(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(ArgumentContext::class);
            }

            return $this->getTypedRuleContext(ArgumentContext::class, $index);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitArgumentList($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ArgumentContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_argument;
        }

        public function parameter(): ?ParameterContext
        {
            return $this->getTypedRuleContext(ParameterContext::class, 0);
        }

        public function scalar(): ?ScalarContext
        {
            return $this->getTypedRuleContext(ScalarContext::class, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitArgument($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ParameterContext extends ParserRuleContext
    {
        /**
         * @var Token|null
         */
        public $name;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_parameter;
        }

        public function PARAMETER_NAME(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::PARAMETER_NAME, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitParameter($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class ScalarContext extends ParserRuleContext
    {
        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_scalar;
        }

        public function copyFrom(ParserRuleContext $context): void
        {
            parent::copyFrom($context);
        }
    }

    class BooleanContext extends ScalarContext
    {
        /**
         * @var Token|null
         */
        public $val;

        public function __construct(ScalarContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function K_TRUE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_TRUE, 0);
        }

        public function K_FALSE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_FALSE, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitBoolean($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class StringContext extends ScalarContext
    {
        /**
         * @var Token|null
         */
        public $val;

        public function __construct(ScalarContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function STRING(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::STRING, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitString($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class DoubleContext extends ScalarContext
    {
        /**
         * @var Token|null
         */
        public $val;

        public function __construct(ScalarContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function DOUBLE(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::DOUBLE, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitDouble($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class IntContext extends ScalarContext
    {
        /**
         * @var Token|null
         */
        public $val;

        public function __construct(ScalarContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function INT(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::INT, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitInt($this);
            }

            return $visitor->visitChildren($this);
        }
    }

    class UserMetadataTargetContext extends ParserRuleContext
    {
        /**
         * @var Token|null
         */
        public $target;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return EZQLParser::RULE_userMetadataTarget;
        }

        public function K_OWNER(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_OWNER, 0);
        }

        public function K_GROUP(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_GROUP, 0);
        }

        public function K_MODIFIER(): ?TerminalNode
        {
            return $this->getToken(EZQLParser::K_MODIFIER, 0);
        }

        public function accept(ParseTreeVisitor $visitor)
        {
            if ($visitor instanceof EZQLVisitor) {
                return $visitor->visitUserMetadataTarget($this);
            }

            return $visitor->visitChildren($this);
        }
    }
}
