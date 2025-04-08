import React, { useState } from 'react';
import { PaymentLink, PaymentStatus } from '../types/PaymentLink';
import { Calendar, DollarSign, User, FileText } from 'lucide-react';

interface PaymentLinkFormProps {
  onSubmit: (link: PaymentLink) => void;
}

export const PaymentLinkForm: React.FC<PaymentLinkFormProps> = ({ onSubmit }) => {
  const [formData, setFormData] = useState<PaymentLink>({
    resellerName: '',
    description: '',
    amount: 0,
    linkDate: new Date(),
    status: PaymentStatus.LINK_SENT
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit(formData);
  };

  return (
    <form 
      onSubmit={handleSubmit} 
      className="bg-white shadow-md rounded-lg p-6 space-y-4"
    >
      <div className="flex items-center space-x-2">
        <User className="text-blue-500" />
        <input 
          type="text" 
          placeholder="Nome da Revendedora"
          value={formData.resellerName}
          onChange={(e) => setFormData({...formData, resellerName: e.target.value})}
          className="w-full border-b border-gray-300 focus:outline-none focus:border-blue-500"
          required
        />
      </div>

      <div className="flex items-center space-x-2">
        <FileText className="text-green-500" />
        <textarea 
          placeholder="Descrição"
          value={formData.description}
          onChange={(e) => setFormData({...formData, description: e.target.value})}
          className="w-full border-b border-gray-300 focus:outline-none focus:border-green-500"
          required
        />
      </div>

      <div className="flex items-center space-x-2">
        <DollarSign className="text-purple-500" />
        <input 
          type="number" 
          placeholder="Valor"
          value={formData.amount}
          onChange={(e) => setFormData({...formData, amount: parseFloat(e.target.value)})}
          className="w-full border-b border-gray-300 focus:outline-none focus:border-purple-500"
          required
        />
      </div>

      <div className="flex items-center space-x-2">
        <Calendar className="text-red-500" />
        <input 
          type="date" 
          value={formData.linkDate.toISOString().split('T')[0]}
          onChange={(e) => setFormData({...formData, linkDate: new Date(e.target.value)})}
          className="w-full border-b border-gray-300 focus:outline-none focus:border-red-500"
          required
        />
      </div>

      <button 
        type="submit" 
        className="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300"
      >
        Adicionar Link de Pagamento
      </button>
    </form>
  );
};
