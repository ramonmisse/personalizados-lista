export enum PaymentStatus {
  LINK_SENT = 'Link Enviado',
  PROMISSORY_GENERATED = 'Promissória Gerada', 
  CREDIT_GENERATED = 'Crédito Gerado',
  CANCELED = 'Cancelado'
}

export interface PaymentLink {
  id?: number;
  resellerName: string;
  description: string;
  amount: number;
  linkDate: Date;
  status: PaymentStatus;
}
