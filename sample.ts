import { HttpClient } from '@angular/common/http';

enum PdfSupportedFontEnum {
    ROBOTO = 'roboto'
}

interface FontInterface {
    id: string;
    name: string;
}

interface StyleInterface {
    marginBottom: number;
    marginLeft: number;
    marginRight: number;
    marginTop: number;
}

class PdfBuilderService {
    public static readonly AVAILABLE_FONTS = new Set<PdfSupportedFontEnum>();
    public static readonly LOADED_FONTS: FontInterface[] = [];

    public static convertCMToPT(num?: number): number {
        return num ? num * 28.3464567 : 0;
    }
}

class Sample {
    private defaultFont = PdfSupportedFontEnum.ROBOTO;

    public constructor(private readonly httpClient: HttpClient) {}

    protected async functionOne(): Promise<void> {
        const fontsToLoad: PdfSupportedFontEnum[] = [];
        const loadedFonts = new Set(PdfBuilderService.LOADED_FONTS.map((font: FontInterface) => font.id));

        PdfBuilderService.AVAILABLE_FONTS.forEach((font: PdfSupportedFontEnum) => {
            if (!loadedFonts.has(font)) {
                fontsToLoad.push(font);
            }
        });

        if (!fontsToLoad.includes(this.defaultFont) && !loadedFonts.has(this.defaultFont)) {
            PdfBuilderService.AVAILABLE_FONTS.add(this.defaultFont);
            fontsToLoad.push(this.defaultFont);
        }

        if (fontsToLoad.length === 0) {
            return;
        }

        const newFonts = await Promise.all(
            fontsToLoad.map((font: PdfSupportedFontEnum) =>
                this.httpClient.get<FontInterface>(`https://sampleurl.io/assets/font/${font}.json`).toPromise()
            )
        );

        newFonts.forEach((font: FontInterface) => PdfBuilderService.LOADED_FONTS.push(font));
    }

    public static functionTwo(var1: number, var2: number, var3: 'left' | 'right' | 'center'): number {
        switch (var3) {
            case 'right':
                var1 += var2;
                break;
            case 'center':
                var1 += var2 / 2;
                break;
        }

        return var1;
    }

    public static function3(style: StyleInterface): StyleInterface {
        if (style.marginTop > 0) {
            style.marginTop = PdfBuilderService.convertCMToPT(style.marginTop);
        }

        if (style.marginBottom > 0) {
            style.marginBottom = PdfBuilderService.convertCMToPT(style.marginBottom);
        }

        if (style.marginLeft > 0) {
            style.marginLeft = PdfBuilderService.convertCMToPT(style.marginLeft);
        }

        if (style.marginRight > 0) {
            style.marginRight = PdfBuilderService.convertCMToPT(style.marginLeft);
        }

        return style;
    }
}
