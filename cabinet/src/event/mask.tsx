import { Mask } from "@tools/Mask";

const mask = new Mask('[name=phone]');
mask.modelNumber('+*(***) ***-**-**', [[2, '7']]);