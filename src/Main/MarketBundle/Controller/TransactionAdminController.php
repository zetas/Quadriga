<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/26/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TransactionAdminController extends CRUDController {
    public function batchActionConfirm(ProxyQueryInterface $selectedModelQuery)
    {

        //$request = $this->get('request');
        $modelManager = $this->admin->getModelManager();

        $selectedModels = $selectedModelQuery->execute();

        // do the merge work here

        try {
            foreach ($selectedModels as $selectedModel) {
                if ($selectedModel->getTransactionType() == 'deposit' && $selectedModel->getStatus() == 'confirmed') {
                    $amount = $selectedModel->getPreFeeAmount();

                    $user = $selectedModel->getUser();
                    if ($selectedModel->getCurrency()->getType() == 'fiat')
                        $user->incrementFiatBalance($amount);
                    else
                        $user->incrementDigitalBalance($amount);

                    $modelManager->update($user);

                }

                $selectedModel->setStatus('completed');

                $modelManager->update($selectedModel);
            }


        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'There was an Error Confirming the Transaction!');

            return new RedirectResponse(
                $this->admin->generateUrl('list',$this->admin->getFilterParameters())
            );
        }

        $this->addFlash('sonata_flash_success', 'Transaction Completed Successfully, user balance updated.');

        return new RedirectResponse(
            $this->admin->generateUrl('list',$this->admin->getFilterParameters())
        );
    }
}