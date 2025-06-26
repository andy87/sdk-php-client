<?php

namespace andy87\sdk\client\helpers;

/**
 * Класс Protocol
 * Содержет константы для обозначения портов HTTP и HTTPS.
 *
 * Protocol Registries
 * https://www.iana.org/protocols
 * https://www.iana.org/assignments/protocol-numbers/protocol-numbers.xhtml
 * https://en.wikipedia.org/wiki/List_of_IP_protocol_numbers
 * https://en.wikipedia.org/wiki/List_of_network_protocols_%28OSI_model%29#Layer_7_(Application_Layer)
 *
 * @package src/hepers
 */
abstract class Protocol
{
    public const HTTP = 'http';

    public const HTTPS = 'https';

    public const WS = 'ws';

    public const WSS = 'wss';

    public const FTP = 'ftp';

    public const FTPS = 'ftps';

    public const SMTP = 'smtp';

    public const IMAP = 'imap';

    public const POP3 = 'pop3';

    public const SFTP = 'sftp';

    public const SSH = 'ssh';
}