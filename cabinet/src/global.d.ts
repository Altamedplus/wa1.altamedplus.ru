interface Window {
  datatable: { [key: string]: any };
  normalCaptcha: boolean
  reloadTable: Function
  callbackSubmit: Function | null
  initResendModal:Function | null
}
declare var isDevelopment: boolean;
