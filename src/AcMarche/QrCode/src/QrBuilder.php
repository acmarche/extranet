<?php

namespace AcMarche\QrCode;

use AcMarche\QrCode\Constant\QrTypeEnum;
use AcMarche\QrCode\DataTypes\EPC;
use AcMarche\QrCode\DataTypes\SMS;
use AcMarche\QrCode\DataTypes\TEXT;
use AcMarche\QrCode\Entity\QrCode;
use AcMarche\QrCode\Form\QrCodeType;
use AcMarche\QrCode\Form\QrEmailType;
use AcMarche\QrCode\Form\QrEpcType;
use AcMarche\QrCode\Form\QrGeoType;
use AcMarche\QrCode\Form\QrMessageType;
use AcMarche\QrCode\Form\QrPhoneNumberType;
use AcMarche\QrCode\Form\QrSmsType;
use AcMarche\QrCode\Form\QrUrlType;
use AcMarche\QrCode\Form\QrWifiType;
use BaconQrCode\Encoder\Encoder;
use LaraZeus\QrCode\DataTypes\Email;
use LaraZeus\QrCode\DataTypes\Geo;
use LaraZeus\QrCode\DataTypes\PhoneNumber;
use LaraZeus\QrCode\DataTypes\WiFi;
use LaraZeus\QrCode\Generator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;

class QrBuilder
{
    public static array $FORMES = ['square' => 'square', 'dot' => 'dot', 'round' => 'round'];

    private string $marcheLogo;

    public static array $corrections = [
        'L' => '7%',
        'M' => '15%',
        'Q' => '25%',
        'H' => '30%',
    ];

    public static array $IMAGE_PERCENTAGE = [
        '0.1' => 'S',
        '0.2' => 'M',
        '0.3' => 'L',
        '0.4' => 'XL',
    ];

   // public static array $FORMATS = ['SVG' => 'SVG', 'PNG' => 'PNG', 'EPS' => 'EPS'];
    public static array $FORMATS = ['SVG' => 'SVG'];

    public static array $MARGINS = [
        0 => 0,
        1 => 1,
        3 => 3,
        7 => 7,
        9 => 9,
    ];

    public function __construct(
        private readonly FormFactoryInterface $formBuilder,
        #[Autowire('%kernel.project_dir%')] private readonly string $projectDir,
        #[Autowire(env: 'QRCODE_DIR')] private readonly string $qrCodeDir,
    ) {
        $this->marcheLogo = $this->projectDir.'/public/images/Marche_logo.png';
    }

    public function buildForm(string $type, QrCode $qrCode): Form|QrCodeType
    {
        return match ($type) {
            QrTypeEnum::SMS->value => $this->formBuilder->create(QrSmsType::class, $qrCode),
            QrTypeEnum::PHONE_NUMBER->value => $this->formBuilder->create(QrPhoneNumberType::class, $qrCode),
            QrTypeEnum::GEO->value => $this->formBuilder->create(QrGeoType::class, $qrCode),
            QrTypeEnum::WIFI->value => $this->formBuilder->create(QrWifiType::class, $qrCode),
            QrTypeEnum::EMAIL->value => $this->formBuilder->create(QrEmailType::class, $qrCode),
            QrTypeEnum::EPC->value => $this->formBuilder->create(QrEpcType::class, $qrCode),
            QrTypeEnum::URL->value => $this->formBuilder->create(QrUrlType::class, $qrCode),
            default => $this->formBuilder->create(QrMessageType::class, $qrCode),
        };
    }

    public function generateDataType(string $type, QrCode $data): Email|EPC|WiFi|SMS|Geo|PhoneNumber|TEXT
    {
        $qr = match ($type) {
            QrTypeEnum::SMS->value => function () use ($data) {
                $qr = new SMS();
                $data->message = str_replace(":", " ", $data->message);
                $qr->create([$data->phoneNumber, $data->message]);

                return $qr;
            },
            QrTypeEnum::PHONE_NUMBER->value => function () use ($data) {
                $qr = new PhoneNumber();
                $qr->create([$data->phoneNumber]);

                return $qr;
            },
            QrTypeEnum::GEO->value => function () use ($data) {
                $qr = new Geo();
                $qr->create([$data->latitude, $data->longitude]);

                return $qr;
            },
            QrTypeEnum::WIFI->value => function () use ($data) {
                $qr = new WiFi();
                $qr->create([
                        [
                            'encryption' => $data->encryption,
                            'ssid' => $data->ssid,
                            'password' => $data->password,
                            'hiddent' => $data->hidden,
                        ],
                    ]
                );

                return $qr;
            },
            QrTypeEnum::EMAIL->value => function () use ($data) {
                $qr = new Email();
                $qr->create([$data->email, $data->subject, $data->message]);

                return $qr;
            },
            QrTypeEnum::EPC->value => function () use ($data) {
                $qr = new EPC();
                $qr->create(
                    [
                        'recipient' => $data->recipient,
                        'amount' => $data->amount,
                        'iban' => $data->iban,
                        'message' => $data->message,
                    ]
                );

                return $qr;
            },
            QrTypeEnum::URL->value => function () use ($data) {
                $qr = new TEXT();
                $qr->create([$data->message]);

                return $qr;
            },
            default => function () use ($data) {
                $qr = new TEXT();
                $qr->create([$data->message]);

                return $qr;
            },
        };

        return $qr();
    }

    public function generate(string $sring, string $format = 'svg'): string
    {
        $fileName = uniqid().'.'.$format;
        $filePath = $this->imageFullPath($fileName);

        $qr = new Generator();

        $qr
            ->encoding(Encoder::DEFAULT_BYTE_MODE_ENCODING)
            ->size(1200)
            ->generate($sring, $filePath);

        return $fileName;
    }

    public function imageFullPath(string $imageName): string
    {
        return $this->projectDir.'/public/'.$this->qrCodeDir.'/'.$imageName;
    }

    public function imageWebPath(string $imageName): string
    {
        return '/'.$this->qrCodeDir.'/'.$imageName;
    }

    public static function getDefaultOptions(array $options = []): array
    {
        return array_merge([
            'size' => '300',
            'type' => 'png',
            'margin' => '1',
            'color' => 'rgba(74, 74, 74, 1)',
            'back_color' => 'rgba(252, 252, 252, 1)',
            'style' => 'square',
            'hasGradient' => false,
            'gradient_form' => 'rgb(69, 179, 157)',
            'gradient_to' => 'rgb(241, 148, 138)',
            'gradient_type' => 'vertical',
            'hasEyeColor' => false,
            'eye_color_inner' => 'rgb(241, 148, 138)',
            'eye_color_outer' => 'rgb(69, 179, 157)',
            'eye_style' => 'square',
            'correction' => 'H',
            'percentage' => 0.2,
            'uploadOptions' => [
                'disk' => 'public',
                'directory' => null,
            ],
        ], $options);
    }
}
