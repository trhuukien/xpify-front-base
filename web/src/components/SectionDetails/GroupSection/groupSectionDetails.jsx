import { memo, useCallback, useMemo } from 'react';
import { PaymentIcon, ViewIcon } from '@shopify/polaris-icons';
import { BlockStack, Box, Card, InlineStack, Layout, Page, Text } from '@shopify/polaris';
import { useBackPage, useRedirectPlansPage } from '~/hooks/section-builder/redirect';
import GallerySlider from '~/components/splide/gallery';
import ProductList from '~/components/product/list';
import ModalInstallSection from '~/components/product/manage';
import SkeletonProduct from '~/components/product/skeleton';
import BannerWarningNotPurchase from '~/components/block/banner/warningPurchase';
import BadgeTag from '~/components/block/badge/tag';
import BadgeStatusSection from '~/components/block/badge/statusSection';
import { Loading } from '@shopify/app-bridge-react';
import NotFound from '~/pages/NotFound.jsx';

const GroupSectionDetails = props => {
  const handleBackPage = useBackPage();
  const handleRedirectPlansPage = useRedirectPlansPage();
  const {
    groupSectionLoading: sectionLoading,
    groupSection,
    childSections,
    purchaseLoading,
    handlePurchase,
    groupSectionError,
    groupSectionReload
  } = props;

  if (groupSectionError?.graphQLErrors?.[0]?.extensions?.category === 'graphql-no-such-entity') {
    return <NotFound />;
  }

  return (
    <>
      {sectionLoading && <Loading />}
      <Page
        backAction={{content: 'Products', onAction: () => handleBackPage()}}
        title={groupSection.name}
        titleMetadata={
          <InlineStack gap={200}>
            <BadgeStatusSection item={groupSection} key={sectionLoading} />
            {
              groupSection?.tags &&
              <BadgeTag tags={groupSection.tags} />
            }
          </InlineStack>
        }
        subtitle={groupSection.version}
        compactTitle
        primaryAction={{
          content: !groupSection?.actions?.purchase ? 'Owned' : 'Purchase',
          disabled: sectionLoading || purchaseLoading || !groupSection?.actions?.purchase,
          helpText: 'Own forever this groupSection.',
          loading: sectionLoading || purchaseLoading,
          onAction: (!sectionLoading && !purchaseLoading) && handlePurchase,
        }}
        secondaryActions={[
          {
            content: 'View in demo site',
            icon: ViewIcon,
            url: groupSection?.demo_link,
            disabled: !groupSection?.demo_link,
            helpText: !groupSection?.demo_link ? 'This product has no demo yet.' : ''
          }
        ]}
      >
        <Layout>
          <Layout.Section>
            <Box>
              <BlockStack gap='400'>
                {
                  (!groupSection.actions?.install) &&
                  <BannerWarningNotPurchase
                    section={groupSection}
                    config={
                      {
                        title: "You cann't use this group section now!",
                        tone: 'warning',
                        action: {
                          content: 'Purchase $' + groupSection.price,
                          icon: PaymentIcon,
                          loading: purchaseLoading,
                          onAction: handlePurchase,
                          disabled: sectionLoading || purchaseLoading
                        }
                      }
                    }
                  />
                }

                <Box>
                  <ModalInstallSection section={groupSection} reloadSection={groupSectionReload} fullWith={false} />
                </Box>

                {groupSection.description && (
                  <Box paddingBlockEnd='600'>
                    <Card title="Description">
                      <Text variant="headingMd">Description</Text>
                      <Box padding="400">
                        <div dangerouslySetInnerHTML={{__html: groupSection.description}}></div>
                      </Box>
                    </Card>
                  </Box>
                )}

                <Box>
                  <BlockStack gap='400'>
                    <BlockStack>
                      <Text variant="headingMd" as="h2">Group</Text>
                      <Text variant="bodyXs" as="p" tone="subdued">"{groupSection.name}" consists of {groupSection.child_ids.length} sections. Sections included in group:</Text>
                    </BlockStack>
                    {childSections.length > 0 ? (
                      <ProductList items={childSections} columns={{sm: 1, md: 2}} />
                    ) : (
                      <SkeletonProduct total={2} columns={{ sm: 1, md: 2 }} />
                    )}
                  </BlockStack>
                </Box>

                <Box>
                  <Card title="Gallery" padding={0}>
                    <GallerySlider gallery={groupSection?.images || []} />
                  </Card>
                </Box>
              </BlockStack>
            </Box>
          </Layout.Section>
        </Layout>
      </Page>
    </>
  );
};

export default memo(GroupSectionDetails);
