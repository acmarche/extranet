<?php

namespace AcMarche\QrCode;

use AcMarche\QrCode\Entity\QrCode as QrCodeEntity;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Exception\ValidationException;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use LaraZeus\QrCode\Generator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class QrCodeGenerator
{
    private string $logoPath;

    public function __construct(
        #[Autowire('%kernel.project_dir%')] private readonly string $projectDir,
        #[Autowire(env: 'QRCODE_DIR')] private readonly string $qrCodeDir,
    ) {
        $this->logoPath = $this->projectDir.'/public/images/Marche_logo.png';
    }

    /**
     * @throws ValidationException
     */
    public function generate(QrCodeEntity $qrCodeEntity): ResultInterface
    {
        $qrGenerator = new Generator();
        $qrGenerator->format('png');


        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: true,
            data: $this->getData($qrCodeEntity),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $qrCodeEntity->size,
            margin: $qrCodeEntity->margin,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: $this->hexToRgb($qrCodeEntity->color, black: true),
            backgroundColor: $this->hexToRgb($qrCodeEntity->colorBackground, white: true),
            labelText: $qrCodeEntity->labelText ?? '',
            labelFont: new OpenSans($qrCodeEntity->labelSize),
            //labelAlignment: $qrCodeEntity->labelAlignment,
            labelTextColor: $this->hexToRgb($qrCodeEntity->labelColor, white: true),
            logoPath: $this->setLogo($qrCodeEntity),
            logoResizeToWidth: $qrCodeEntity->logoSize,
            logoPunchoutBackground: false,
        );

        return $builder->build();
    }
    public function saveToFile(ResultInterface $result, QrCodeEntity $qrCode): void
    {
        $name = $qrCode->uuid.'.png';
        $filePath = $this->qrCodeDir.DIRECTORY_SEPARATOR.$name;
        $result->saveToFile($filePath);
        $qrCode->filePath = DIRECTORY_SEPARATOR.$filePath;
    }

    private function setLogo(QrCodeEntity $qrCodeEntity): string
    {
        if ($qrCodeEntity->addDefaultLogo) {
            return $this->logoPath;
        }

        if ($qrCodeEntity->logo instanceof UploadedFile) {
            $logo = $qrCodeEntity->logo;
            $filePath = $this->projectDir.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$this->qrCodeDir.DIRECTORY_SEPARATOR;
            $fileName = uniqid().'.'.$logo->guessExtension() ?? '.png';
            $logo->move($filePath, $fileName);
            $qrCodeEntity->logoPath = $filePath.DIRECTORY_SEPARATOR.$fileName;

            return $qrCodeEntity->logoPath;
        }

        return '';
    }

    private function hexToRgb(?string $hex, bool $black = false, bool $white = false): Color
    {
        if (!$hex) {
            if ($white) {
                return new Color(255, 255, 255);
            }

            return new Color(0, 0, 0);
        }

        // Remove '#' if present
        $hex = ltrim($hex, '#');

        // Expand shorthand hex code (e.g., #03F) to full form (#0033FF)
        if (strlen($hex) === 3) {
            $hex = str_repeat($hex[0], 2).str_repeat($hex[1], 2).str_repeat($hex[2], 2);
        }

        // Convert hex to RGB values
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return new Color($r, $g, $b);
    }


}
