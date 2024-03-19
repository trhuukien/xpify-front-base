import { memo, useState } from 'react';
import { BlockStack, InlineGrid, Modal } from '@shopify/polaris';
import ProductCard from '~/components/product/card';
import ModalProduct from '~/components/product/modal';
import ModalInstall from '~/components/block/modal/install'

function ProductList({items, columns}) {
  console.log('re-render-productList');
  const [isShowPopup, setIsShowPopup] = useState(false);
  const [isShowPopupInstall, setIsShowPopupInstall] = useState(false);
  const [section, setSection] = useState(undefined);

  return (
    <InlineGrid columns={columns} gap={600}>
      {
        items.map(item => {
          return <ProductCard key={item.entity_id} item={item} setSection={setSection} setIsShowPopup={setIsShowPopup} setIsShowPopupInstall={setIsShowPopupInstall} />
        })
      }

      <ModalProduct section={section} isShowPopup={isShowPopup} setIsShowPopup={setIsShowPopup} />
      <ModalInstall section={section} isShowPopup={isShowPopupInstall} setIsShowPopup={setIsShowPopupInstall} />
    </InlineGrid>
  );
}

export default memo(ProductList);