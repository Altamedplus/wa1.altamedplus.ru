import { integ } from '@rocet/integration';
import { $, Rocet } from '@rocet/rocet';
import { PDFDocument, StandardFonts, rgb } from 'pdf-lib';

const $prewContaner = $('[data-prev-pdf]');
if ($prewContaner.length != 0) { 
    const urls: string[] = [];
    $('[data-prev-url]').each((e: Rocet) => {
        let pdf = e.data('prevUrl')
        if (pdf && isPdf(pdf)) { 
            urls.push(pdf);
        }
    });
    gluePdf(urls).then((glueUrl) => { 
         console.log(glueUrl)
        const iframe = $(<embed src={glueUrl} ></embed>);
        $prewContaner.add(iframe);
    })
}

async function gluePdf(urls: string[]) {
    const pdfDocs = await Promise.all(urls.map(async url => {
        const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer());
        return PDFDocument.load(existingPdfBytes);
    }));
    const mergedPdf = await PDFDocument.create();
    for (const pdfDoc of pdfDocs) {
        const copiedPages = await mergedPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
        copiedPages.forEach((page) => {
            mergedPdf.addPage(page);
        });
    }
    const mergedPdfBytes = await mergedPdf.save();
    const blob = new Blob([mergedPdfBytes as BlobPart], { type: 'application/pdf' });
    return URL.createObjectURL(blob);
}
function isPdf(url:string):boolean { 
    return url.split('.').pop()?.toLowerCase() == 'pdf';
}