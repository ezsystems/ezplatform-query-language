grammar EZQL;

stmt:
    selectLocation|selectContent|selectContentInfo;

selectLocation:
    K_SELECT K_LOCATION properties=selectProperties;

selectContent:
    K_SELECT K_CONTENT properties=selectProperties;

selectContentInfo:
    K_SELECT K_CONTENT K_INFO properties=selectProperties;

selectProperties:
    (K_FILTER K_BY filter=expr)?
    (K_QUERY query=expr)?
    (K_ORDER K_BY sortClauses=sortClauseList)?
    (K_LIMIT limit=argument)?
    (K_OFFSET offset=argument)?
    ;

sortClauseList:
    sortClause (',' sortClause)*;

sortClause:
    name=ID sortOrder;

sortOrder:
    order=(K_ASC|K_DESC);

expr:
      K_MATCH K_ALL                                                             #matchAllExpr
    | K_MATCH K_NONE                                                            #matchNoneExpr
    | K_IS K_MAIN K_LOCATION                                                    #isMainLocationExpr
    | K_IS K_NOT K_MAIN K_LOCATION                                              #isNotMainLocationExpr
    | K_IS flag=(K_VISIBLE|K_HIDDEN)                                            #visibilityExpr
    | K_FIELD field=ID K_IS K_EMPTY                                             #isFieldEmptyExpr
    | K_FIELD field=ID K_IS K_NOT K_EMPTY                                       #isNotFieldEmptyExpr
    | K_FIELD field=ID op=operator val=value                                    #fieldExpr
    | K_FIELD K_RELATION field=ID op=operator val=value                         #fieldRelationExpr
    | K_LOCATION K_PRIORITY op=operator val=value                               #locationPriorityExpr
    | K_CREATED op=operator val=value                                           #createdExpr
    | K_MODIFIED op=operator val=value                                          #modifiedExpr
    | K_FULLTEXT val=value fuzziness? boosting?                                 #fulltextExpr
    | target=userMetadataTarget op=operator val=value                           #userMetadataExpr
    | clazz=ID op=operator val=value                                            #criterionExpr
    | left=expr K_AND right=expr                                                #andExpr
    | left=expr K_OR right=expr                                                 #orExpr
    | '(' expr ')'                                                              #innerExpr
    ;

operator:
      op=(EQ|K_IN|GT|LT|GTE|LTE|K_LIKE|K_BETWEEN|K_CONTAINS)                    #buildInOperator
    | K_NOT K_IN                                                                #notInOperator
    | NEQ                                                                       #notEQ
    ;

fuzziness:
    K_FUZZINESS val=value;

boosting:
    K_BOOST fieldBoost (',' fieldBoost)*;

fieldBoost:
    field=ID '^' val=value;

value:
    argument|argumentList|argumentRange;

argumentRange:
    a=argument '..' b=argument;

argumentList:
    '(' argument (',' argument)* ')';

argument:
    parameter | scalar;

parameter:
    name=PARAMETER_NAME;

scalar:
      val=INT                #int
    | val=(K_TRUE|K_FALSE)   #boolean
    | val=STRING             #string
    | val=DOUBLE             #double
    ;

userMetadataTarget:
    target=(K_OWNER|K_GROUP|K_MODIFIER);

K_ALL: [Aa][Ll][Ll];
K_AND: [Aa][Nn][Dd];
K_ASC: [Aa][Ss][Cc];
K_BETWEEN: [Bb][Ee][Tt][Ww][Ee][Ee][Nn];
K_BOOST: [Bb][Oo][Oo][Ss][Tt];
K_BY: [Bb][Yy];
K_CONTAINS: [Cc][Oo][Nn][Tt][Aa][Ii][Nn][Ss];
K_CONTENT: [Cc][Oo][Nn][Tt][Ee][Nn][Tt];
K_CREATED: [Cc][Rr][Ee][Aa][Tt][Ee][Dd];
K_DESC: [Dd][Ee][Ss][Cc];
K_EMPTY: [Ee][Mm][Pp][Tt][Yy];
K_FALSE: [Ff][Aa][Ll][Ss][Ee];
K_FULLTEXT: [Ff][Uu][Ll][Ll][Tt][Ee][Xx][Tt];
K_FUZZINESS: [Ff][Uu][Zz][Zz][Ii][Nn][Ee][Ss][Ss];
K_FIELD: [Ff][Ii][Ee][Ll][Dd];
K_FILTER: [Ff][Ii][Ll][Tt][Ee][Rr];
K_GROUP: [Gg][Rr][Oo][Uu][Pp];
K_HIDDEN: [Hh][Ii][Dd][Dd][Ee][Nn];
K_IN: [Ii][Nn];
K_INFO: [Ii][Nn][Ff][Oo];
K_IS: [Ii][Ss];
K_LIKE: [Ll][Ii][Kk][Ee];
K_LIMIT: [Ll][Ii][Mm][Ii][Tt];
K_LOCATION: [Ll][Oo][Cc][Aa][Tt][Ii][Oo][Nn];
K_MAIN: [Mm][Aa][Ii][Nn];
K_MATCH: [Mm][Aa][Tt][Cc][Hh];
K_MODIFIER: [Mm][Oo][Dd][Ii][Ff][Ii][Ee][Rr];
K_MODIFIED: [Mm][Oo][Dd][Ii][Ff][Ii][Ee][Dd];
K_NONE: [Nn][Oo][Nn][Ee];
K_NOT: [Nn][Oo][Tt];
K_OFFSET: [Oo][Ff][Ff][Ss][Ee][Tt];
K_OR: [Oo][Rr];
K_ORDER: [Oo][Rr][Dd][Ee][Rr];
K_OWNER: [Oo][Ww][Nn][Ee][Rr];
K_PRIORITY: [Pp][Rr][Ii][Oo][Rr][Ii][Tt][Yy];
K_RELATION: [Rr][Ee][Ll][Aa][Tt][Ii][Oo][Nn];
K_QUERY: [Qq][Uu][Ee][Rr][Yy];
K_SELECT: [Ss][Ee][Ll][Ee][Cc][Tt];
K_TRUE: [Tt][Rr][Uu][Ee];
K_VISIBLE: [Vv][Ii][Ss][Ii][Bb][Ll][Ee];

EQ: '=';
NEQ: '!=';
GT: '>';
GTE: '>=';
LT: '<';
LTE: '<=';

INT: ('+'|'-')?('0'|[1-9][0-9]*);
DOUBLE: '-'?[0-9][1-9]*('.'[0-9]+)?;
STRING: '"' (ESCAPE_SEQ|.)*? '"';
PARAMETER_NAME: ':'([a-zA-Z_][a-zA-Z0-9_]*);
ID: [a-zA-Z_][a-zA-Z0-9_\\]*;
WS: [ \t\r\n] -> skip;

fragment ESCAPE_SEQ: '\\"'|'\\\\';
