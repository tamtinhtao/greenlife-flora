<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| KIỂM TRA CHỨNG CHỈ CA MYSQL
|--------------------------------------------------------------------------
|
| File này được entrypoint.sh gọi trước khi Laravel kết nối Aiven.
| Mục đích:
| - kiểm tra biến MYSQL_ATTR_SSL_CA;
| - kiểm tra file có tồn tại và đọc được;
| - kiểm tra nội dung có dạng chứng chỉ PEM.
|
*/

$caPath = getenv('MYSQL_ATTR_SSL_CA');


if ($caPath === false || trim($caPath) === '') {

    fwrite(
        STDERR,
        "MYSQL_ATTR_SSL_CA is not set.\n"
    );

    exit(1);
}


if (!is_file($caPath)) {

    fwrite(
        STDERR,
        "MySQL CA file does not exist: {$caPath}\n"
    );

    exit(1);
}


if (!is_readable($caPath)) {

    fwrite(
        STDERR,
        "MySQL CA file is not readable: {$caPath}\n"
    );

    exit(1);
}


$content = file_get_contents($caPath);


if ($content === false || trim($content) === '') {

    fwrite(
        STDERR,
        "MySQL CA file is empty or cannot be read.\n"
    );

    exit(1);
}


if (
    !str_contains(
        $content,
        '-----BEGIN CERTIFICATE-----'
    )
    ||
    !str_contains(
        $content,
        '-----END CERTIFICATE-----'
    )
) {

    fwrite(
        STDERR,
        "MySQL CA file is not a valid PEM certificate.\n"
    );

    exit(1);
}


fwrite(
    STDOUT,
    "MySQL CA certificate check passed.\n"
);

exit(0);