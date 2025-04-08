import React from 'react';
import { PaymentLink, PaymentStatus } from '../types/PaymentLink';
import { RefreshCw, XCircle, CheckCircle } from 'lucide-react';

interface PaymentLinkListProps {
  links: PaymentLink[];
  onStatusUpdate: (id: number, status: PaymentStatus) => void;
}

const getStatusColor = (status: PaymentStatus) => {
  switch(status) {
    case PaymentStatus.LINK_SENT: return 'bg-yellow-100 text-yellow-800';
    case PaymentStatus.PROMISSORY_GENERATED: return 'bg-blue-100 text-blue-800';
    case PaymentStatus.CREDIT_GENERATED: return 'bg-green-100 text-green-800';
    case PaymentStatus.CANCELED: return 'bg-red-100 text-red-800';
  }
};

export const PaymentLinkList: React.FC<PaymentLinkListProps> = ({ links, onStatusUpdate }) => {
  return (
    <div className="bg-white shadow-md rounded-lg overflow-hidden">
      <table className="w-full">
        <thead className="bg-gray-100">
          <tr>
            <th className="p-3 text-left">Revendedora</th>
            <th className="p-3 text-left">Descrição</th>
            <th className="p-3 text-left">Valor</th>
            <th className="p-3 text-left">Data</th>
            <th className="p-3 text-left">Status</th>
            <th className="p-3 text-left">Ações</th>
          </tr>
        </thead>
        <tbody>
          {links.map((link) => (
            <tr key={link.id} className="border-b hover:bg-gray-50">
              <td className="p-3">{link.resellerName}</td>
              <td className="p-3">{link.description}</td>
              <td className="p-3">R$ {link.amount.toFixed(2)}</td>
              <td className="p-3">{link.linkDate.toLocaleDateString()}</td>
              <td className="p-3">
                <span className={`px-2 py-1 rounded ${getStatusColor(link.status)}`}>
                  {link.status}
                </span>
              </td>
              <td className="p-3 flex space-x-2">
                {link.status !== PaymentStatus.CREDIT_GENERATED && (
                  <button 
                    onClick={() => onStatusUpdate(link.id!, PaymentStatus.CREDIT_GENERATED)}
                    className="text-green-500 hover:text-green-700"
                    title="Gerar Crédito"
                  >
                    <CheckCircle />
                  </button>
                )}
                {link.status !== PaymentStatus.CANCELED && (
                  <button 
                    onClick={() => onStatusUpdate(link.id!, PaymentStatus.CANCELED)}
                    className="text-red-500 hover:text-red-700"
                    title="Cancelar"
                  >
                    <XCircle />
                  </button>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};
